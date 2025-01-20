<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('rooms.{roomId}', function ($user, $roomId) {
    return $user->rooms()->where('room_id', $roomId)->exists();
});

Broadcast::channel('rooms', function (){
    return true;
});
