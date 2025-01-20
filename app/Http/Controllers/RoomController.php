<?php

namespace App\Http\Controllers;

use App\Events\UserJoinedRoom;
use App\Events\UserLeftRoom;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\Room;
use App\Models\User;

class RoomController extends Controller
{
    public function index()
    {
        $userRooms = auth()->user()->rooms()->latest()->paginate(3);
        return view('rooms.index', compact('userRooms'));
    }

    public function create()
    {
        $users = User::where('id', '!=', auth()->id())->get();
        return view('rooms.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoomRequest $request)
    {
        $validated = $request->validated();
        $room = Room::create($validated);
        $room->users()->attach(auth()->id());
        $validated['users'] = $validated['users'] ?? [];
        if (!is_null($validated['users'])){
            $room->users()->syncWithoutDetaching($validated['users']);
        }
        return redirect()->route('rooms.index')->with('message', 'Комната успешно создана');
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        $room->load('users');
        return view('rooms.edit', compact('room'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoomRequest $request, Room $room)
    {
        $validated = $request->validated();
        $room->update($validated);
        return redirect()->route('rooms.show', $room)->with('message', 'Успешно обновлено');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        $room->delete();
        return redirect()->route('rooms.index')->with('message', 'Комната была удалена');
    }

    public function enter(Room $room)
    {
        $room->load(['users', 'messages.sender']);
        event(new UserJoinedRoom($room));
        return view('rooms.show', compact('room'));
    }

    public function leave(Room $room)
    {
        event(new UserLeftRoom($room));
        return redirect()->route('rooms.index');
    }
}
