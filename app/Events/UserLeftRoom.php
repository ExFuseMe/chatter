<?php

namespace App\Events;

use App\Http\Resources\UserResource;
use App\Models\Room;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class UserLeftRoom
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private string $key;
    private Room $room;
    private UserResource $userResource;

    public function __construct(Room $room)
    {
        $this->room = $room;
        $user = auth()->user();
        $this->userResource = new UserResource($user);
        $this->key = 'room.' . $room->id . '.users';
        $usersCollection = Cache::get($this->key);

        $collection = $usersCollection->filter(function ($item) use ($user) {
            return $item->id === $user;
        });

        Cache::put($this->key, $collection);
    }
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('rooms'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'user_left_room';
    }

    public function broadcastWith(): array
    {
        return [
            'room' => $this->room->id,
            'user' => $this->userResource,
            'usersCount' => Cache::get($this->key)->count(),
        ];
    }
}
