<?php

namespace App\Models;

use App\Events\NewMessageEvent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['text', 'sender_id', 'room_id'];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    protected static function booted()
    {
        parent::booted();
        static::created(function (Message $message) {
            event(new NewMessageEvent($message));
        });
    }
}
