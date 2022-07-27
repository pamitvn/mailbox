<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\PAM\Enums\ProductStatus;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class ProductCheckLiveFacebookCommand extends Command
{
    protected $signature = 'product:check-live-facebook {serviceId}';

    protected $description = 'Command description';

    private Client $client;

    public function __construct()
    {
        parent::__construct();

        $this->client = new Client([
            'http_errors' => false,
        ]);
    }

    public function handle()
    {
        $serviceId = $this->argument('serviceId');
        $checkEndpoint = config('services.facebook.check_live');

        Log::info(sprintf('Service::%s Starting check live facebook', $serviceId));

        if (blank($checkEndpoint)) {
            $this->error('Missing facebook check live endpoint');

            return;
        }

        $products = Product::query()
            ->whereServiceId($serviceId)
            ->whereStatus(ProductStatus::LIVE);

        $products->chunk(1000, function (Collection $chunkData) use ($checkEndpoint) {
            $data = $chunkData
                ->keyBy('id')
                ->map(fn (Product $product) => [
                    'id' => $product->id,
                    'uid' => Arr::get(explode('|', $product->payload), 0),
                ])
                ->filter(fn ($ite) => filled($ite));

            $uuid = $data->pluck('uid');

            $response = $this->client->post($checkEndpoint, [
                'json' => [
                    'uuid' => $uuid->toArray(),
                ],
            ]);

            $body = collect(json_decode($response->getBody()->getContents(), true));

            $groupByStatus = $body
                ->mapToGroups(fn ($group, $ite) => [$group => Arr::get($data->first(fn ($product) => $product['uid'] === $ite), 'id')])
                ->filter(fn ($ite) => filled($ite));

            $groupByStatus->each(function (\Illuminate\Support\Collection $ids, $group) {
                $status = match ($group) {
                    'die' => ProductStatus::DIE,
                    'live' => ProductStatus::LIVE,
                    default => null,
                };

                Product::query()
                    ->whereIn('id', $ids->toArray())
                    ->update([
                        'status' => $status,
                    ]);
            });
        });
    }
}
