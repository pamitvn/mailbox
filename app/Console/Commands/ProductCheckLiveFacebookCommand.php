<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Service;
use App\PAM\Enums\ProductStatus;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

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

        if (blank($checkEndpoint)) {
            $this->error('Missing facebook check live endpoint');

            return;
        }

        $service = Service::whereIsLocal(true)
            ->orWhereJsonContains('extras->check_live_facebook', true)
            ->find($serviceId);

        if (! $service) {
            $this->error('Check live is disabled');

            return;
        }

        $messages = [
            'start' => "Service::$serviceId Starting check live facebook",
            'status' => "Service::$serviceId %s items has status %s",
        ];

        pam_system_log()->info($messages['start']);

        $products = Product::query()
            ->whereServiceId($serviceId)
            ->whereStatus(ProductStatus::LIVE)
            ->get();

        foreach ($products->chunk(1000) as $chunkData) {
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

            $groupByStatus->each(function (\Illuminate\Support\Collection $ids, $group) use ($messages) {
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

                $logMessage = sprintf($messages['status'], $ids->count(), $status);

                pam_system_log()->info($logMessage);
                $this->info($logMessage);
            });
        }
        $this->info('Completed');
    }
}
