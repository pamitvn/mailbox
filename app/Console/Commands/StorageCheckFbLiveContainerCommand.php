<?php

namespace App\Console\Commands;

use App\Models\StorageContainer;
use App\PAM\Enums\ProductStatus;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class StorageCheckFbLiveContainerCommand extends Command
{
    protected $signature = 'storage:check-fb-live-container';

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
        $messages = [
            'start' => 'Storage::Containers Starting check live facebook',
            'status' => 'Storage::Containers %s items has status %s',
        ];

        $this->info($messages['start']);
        pam_system_log()->info($messages['start']);

        $checkEndpoint = config('services.facebook.check_live');

        if (blank($checkEndpoint)) {
            $this->error('Missing facebook check live endpoint');

            return;
        }

        $containers = StorageContainer::query()
            ->inRandomOrder()
            ->whereStatus(ProductStatus::LIVE);

        $containers->chunk(1000, function (Collection $chunkData) use ($checkEndpoint, $messages) {
            $data = $chunkData
                ->keyBy('id')
                ->map(fn (StorageContainer $container) => [
                    'id' => $container->id,
                    'uid' => Arr::first(explode('|', $container->payload)),
                ])
                ->filter(fn ($ite) => filled($ite));

            $response = $this->client->post($checkEndpoint, [
                'json' => [
                    'uuid' => $data->pluck('uid')->toArray(),
                ],
            ]);

            $body = collect(json_decode($response->getBody()->getContents(), true));

            $groupByStatus = $body
                ->mapToGroups(fn ($group, $ite) => [$group => Arr::get($data->first(fn ($product) => $product['uid'] === $ite), 'id')])
                ->filter(fn ($ite) => filled($ite));

            $groupByStatus->each(function (Collection $ids, $group) use ($messages) {
                $status = match ($group) {
                    'die' => ProductStatus::DIE,
                    'live' => ProductStatus::LIVE,
                    default => null,
                };

                if (blank($status)) {
                    return;
                }

                StorageContainer::query()
                    ->whereIn('id', $ids->toArray())
                    ->update([
                        'status' => $status,
                    ]);

                $logMessage = sprintf($messages['status'], $ids->count(), $status);

                pam_system_log()->info($logMessage);
                $this->info($logMessage);
            });
        });

        $this->info('Completed');
    }
}
