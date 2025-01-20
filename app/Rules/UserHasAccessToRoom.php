<?php

namespace App\Rules;


use Illuminate\Contracts\Validation\Rule;

class UserHasAccessToRoom implements Rule
{

    public function passes($attribute, $value)
    {
        return auth()->user()->rooms()->where('room_id', $value)->exists();
    }

    public function message(): string
    {
        return "Нет доступа";
    }
}
