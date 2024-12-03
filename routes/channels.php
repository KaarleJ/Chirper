<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Auth;
use App\Models\Chat;

Broadcast::channel('user{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

Broadcast::channel('chat{chatId}', function ($user, $chatId) {
    $chat = Chat::find($chatId);

    return $chat->user_one_id === Auth::id() || $chat->user_two_id === Auth::id();
});
