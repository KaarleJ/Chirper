<?php

namespace App\Services;

use App\Models\Chat;
use App\Events\GotMessage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MessageService
{
  /**
   * Send a new message in a chat.
   */
  public function sendMessage(Chat $chat, array $data)
  {
    $user = User::find(Auth::id());
    $chat->messages()->create([
      'sender_id' => $user->id,
      'content' => $data['content'],
    ]);

    broadcast(new GotMessage($chat->messages()->latest()->first(), $user))->toOthers();
  }
}