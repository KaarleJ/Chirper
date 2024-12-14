<?php

namespace App\Services;

use App\Models\Chat;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;

class ChatService
{

  protected UserService $userService;

  public function __construct(UserService $userService)
  {
    $this->userService = $userService;
  }

  /**
   * Get all chats for a specific user.
   */
  public function getUserChats(int $userId)
  {
    $chats = Chat::where('user_one_id', $userId)
      ->orWhere('user_two_id', $userId)
      ->with([
        'userOne',
        'userTwo',
        'messages' => function ($query) {
          $query->latest()->first();
        }
      ])
      ->withCount([
        'messages as unread_count' => function ($query) use ($userId) {
          $query->where('sender_id', '!=', $userId)
            ->whereNull('read_at');
        }
      ])
      ->get();

    $follows = $this->userService->getUsersFollowed($userId);

    return [
      'chats' => $chats,
      'follows' => $follows,
    ];
  }

  /**
   * Create a new chat for a specific user.
   */
  public function createChat(int $userId, array $data)
  {
    $chat = new Chat($data);
    $chat->user_id = $userId;
    $chat->save();

    return $chat;
  }

  /**
   * Get details of a specific chat.
   */
  public function getChatDetails(Chat $chat)
  {
    $chat->load(['userOne', 'userTwo']);
    $messages = $chat->messages()->orderBy('created_at', 'desc')->get();
    $chats = $this->getUserChats(Auth::id());
    // Merge the chats array with the current chat and messages
    return array_merge($chats, [
      'currentChat' => $chat,
      'messages' => $messages,
    ]);
  }

  /**
   * Mark all messages in a chat as read for a specific user.
   */
  public function markMessagesAsRead(Chat $chat)
  {
    $chat->messages()
      ->where('sender_id', '!=', Auth::id())
      ->whereNull('read_at')
      ->update(['read_at' => now()]);

    return 'success';
  }
}