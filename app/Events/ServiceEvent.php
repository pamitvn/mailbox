<?php

namespace App\Events;

use App\Models\Product;
use App\Models\Service;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithBroadcasting;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ServiceEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, InteractsWithBroadcasting, SerializesModels;

    private ?Service $service;

    public function __construct(Service|string|int $service)
    {
        $this->broadcastVia('pusher');

        $this->service = ! $service instanceof Service ? Service::find($service) : $service;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('service');
    }

    public function broadcastAs(): string
    {
        return 'stocks';
    }

    public function broadcastWith()
    {
        return array_merge(Service::find($this->service->id)->toArray(), [
            'in_stock_count' => Product::query()
                ->whereServiceId($this->service->id)
                ->select(['id'])
                ->withoutBought()
                ->count(),
        ]);
    }
}
