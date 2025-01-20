<?php

namespace App\Events;

use App\Http\Resources\UserResource;
use App\Models\Room;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageTypingEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private Room $room;
    private User $user;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, Room $room)
    {
        $this->room = $room;
        $this->user = $user;
    }
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('rooms.'.$this->room->id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'typing';
    }

    public function broadcastWith(): array
    {
        return [
            'user' => new UserResource($this->user),
        ];
    }
}
