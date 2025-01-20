<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserRoomSeeder extends Seeder
{
    public function run(): void
    {
        foreach (User::all() as $user) {
            $user->rooms()->syncWithoutDetaching(Room::all()->pluck('id'));
        }
    }
}
