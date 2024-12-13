<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;
use App\Models\User;
use App\Models\Chat;

class GotMessage implements ShouldBroadcast
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public Message $message;

  public User $user;

  public Chat $chat;

  /**
   * Create a new event instance.
   */
  public function __construct(Message $message, User $user)
  {
    $this->message = $message;
    $this->user = $user;
    $this->chat = $message->chat;
  }

  /**
   * Get the channels the event should broadcast on.
   *
   * @return array<int, \Illuminate\Broadcasting\Channel>
   */
  public function broadcastOn()
  {
    $receiver = $this->chat->userTwo->id === $this->user->id ? $this->chat->userOne : $this->chat->userTwo;

    return [
      new PrivateChannel('chat' . $this->chat->id),
      new PrivateChannel('user' . $receiver->id),
    ];
  }

  public function broadcastWith()
  {
    return [
      'message' => $this->message->load('sender'),
    ];
  }
}