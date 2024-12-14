<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageRequest;
use App\Services\MessageService;
use App\Models\Chat;

class MessageController extends Controller
{
  protected MessageService $messageService;

  public function __construct(MessageService $messageService)
  {
    $this->messageService = $messageService;
  }

  /**
   * Send a new message in a specific chat.
   */
  public function store(MessageRequest $request, Chat $chat)
  {
    $this->messageService->sendMessage($chat, $request->validated());
  }
}
