<?php

namespace App\Events\Sockets;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithBroadcasting;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserMessageEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, InteractsWithBroadcasting, SerializesModels;

    protected int|string $userId;
    protected array $payload;

    public function __construct(int|string $userId, array $payload)
    {
        $this->broadcastVia('pusher');

        $this->userId = $userId;
        $this->payload = $payload;
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('user.' . $this->userId);
    }

    public function broadcastAs(): string
    {
        return 'message';
    }

    public function broadcastWith(): array
    {
        return $this->payload;
    }

}
