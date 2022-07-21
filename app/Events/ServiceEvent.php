<?php

namespace App\Events;

use App\Models\Service;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ServiceEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private ?Service $service;

    public function __construct(Service|string|int $service)
    {
        $this->service = ! $service instanceof Service ? Service::find($service) : $service;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('service');
    }

    public function broadcastAs(): string
    {
        return 'stock';
    }

    public function broadcastWith(): array
    {
        return $this->service?->refresh()?->toArray() ?? [];
    }
}
