<?php

namespace Database\Seeders;

use App\Models\Message;
use App\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Room::all() as $room) {
            Message::factory()->create(['room_id' => $room->id, 'sender_id' => 1]);
        }
    }
}
