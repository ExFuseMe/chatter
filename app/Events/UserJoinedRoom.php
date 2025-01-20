<?php

namespace App\Events;

use App\Http\Resources\UserResource;
use App\Models\Room;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class UserJoinedRoom implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private string $key;
    private Room $room;
    private UserResource $userResource;

    public function __construct(Room $room)
    {
        $this->key = 'room.' . $room->id . '.users';
        $this->room = $room;
        $this->userResource = new UserResource(auth()->user());
        if (Cache::has($this->key)) {
            $users = Cache::get($this->key);
            $existingUser = $users->first(function ($user) {
                return $user['id'] === auth()->id();
            });
            if (!$existingUser) {
                $users->push($this->userResource);
                Cache::put($this->key, $users);
            }
        } else {
            Cache::put($this->key, collect([$this->userResource]));
        }
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('rooms'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'user_joined_room';
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
