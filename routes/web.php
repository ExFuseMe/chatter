<?php

use App\Http\Controllers\MessageController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::resource('rooms', RoomController::class);
    Route::get('rooms/{room}/chat', [RoomController::class, 'enter'])->name('rooms.enter');
    Route::put('rooms/{room}/chat/leave', [RoomController::class, 'leave'])->name('rooms.leave');
    Route::post('messages', [MessageController::class, 'store'])->name('messages.store');
    Route::put('rooms/{room}/typing', [MessageController::class, 'typing'])->name('messages.typing');
});
