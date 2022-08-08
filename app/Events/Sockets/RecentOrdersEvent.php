<?php

namespace App\Events\Sockets;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithBroadcasting;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RecentOrdersEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, InteractsWithBroadcasting, SerializesModels;

    public function __construct(protected Order|string|int $order)
    {
        $this->order = ! $this->order instanceof Order ? Order::withRecentOrder()->find($this->order) : $this->order;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('recent-orders');
    }

    public function broadcastAs(): string
    {
        return 'create';
    }

    public function broadcastWith(): array
    {
        $this->order->user->username = $this->order?->user?->mask_username;

        $time = $this->order->created_at->diffForHumans();

        return collect($this->order->toArray())
            ->only(['id', 'price', 'quantity', 'service', 'user', 'created_at'])
            ->put('created_at', $time)
            ->toArray();
    }
}
