<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RaceChatModeration implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly string $action,    // 'muted' | 'unmuted'
        public readonly int    $userId,
        public readonly string $username,
        public readonly ?int   $expiresIn, // seconds, null = permanent
    ) {}

    public function broadcastOn(): array
    {
        return [new Channel('chat-moderation')];
    }

    public function broadcastAs(): string
    {
        return 'moderation';
    }
}
