<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RaceChatMessage implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly string $raceKey,   // e.g. "2025_5"
        public readonly string $username,
        public readonly string $message,
        public readonly string $teamColor,
        public readonly string $timestamp,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel("race-chat.{$this->raceKey}"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message';
    }
}
