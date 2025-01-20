<?php

namespace App\Http\Requests;

use App\Rules\UserHasAccessToRoom;
use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'text' => ['required', 'string'],
            'room_id' => ['required', 'integer', 'exists:rooms,id', new UserHasAccessToRoom()],
        ];
    }
}
