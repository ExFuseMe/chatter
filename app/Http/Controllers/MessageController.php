<?php

namespace App\Http\Controllers;

use App\Events\MessageTypingEvent;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use App\Models\Room;

class MessageController extends Controller
{
    public function store(StoreMessageRequest $request)
    {
        $validated = $request->validated();
        $validated['sender_id'] = auth()->id();
        $message = Message::create($validated);
        return new MessageResource($message);
    }

    public function typing(Room $room)
    {
        return event(new MessageTypingEvent(auth()->user(), $room));
    }
}
