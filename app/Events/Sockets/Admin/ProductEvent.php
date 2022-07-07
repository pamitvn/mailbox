<?php

namespace App\Events\Sockets\Admin;

use App\Models\Product;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private string|int $serviceId;
    private Product $product;
    private bool $isCreate;

    public function __construct(string|int $serviceId, Product $product, $isCreate = false)
    {
        $this->serviceId = $serviceId;
        $this->product = $product;
        $this->isCreate = $isCreate;
    }

    public function broadcastOn(): Channel
    {
        return new PrivateChannel('user.admin');
    }

    public function broadcastAs(): string
    {
        return sprintf('service.%s.product.%s', $this->serviceId, $this->as());
    }

    public function broadcastWith(): array
    {
        return $this->product->refresh()->toArray();
    }

    protected function as(): string
    {
        return $this->isCreate ? 'create' : 'update';
    }

}
