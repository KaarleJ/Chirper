<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Auth;
use App\Models\Chat;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat{chatId}', function ($user, $chatId) {
    $chat = Chat::find($chatId);

    return $chat->user_one_id === Auth::id() || $chat->user_two_id === Auth::id();
});
