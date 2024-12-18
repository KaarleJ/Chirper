<?php

use App\Models\User;
use App\Models\Chat;
use App\Models\Message;
use App\Services\MessageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use App\Events\GotMessage;
use Tests\TestCase;
use Illuminate\Support\Facades\Event;

uses(TestCase::class, RefreshDatabase::class);

describe('MessageService', function () {
  beforeEach(function () {
    $this->messageService = app(MessageService::class);
    $this->authUser = User::factory()->create();
    Auth::login($this->authUser);
    $this->chat = Chat::factory()->create([
      'user_one_id' => $this->authUser->id,
      'user_two_id' => User::factory()->create()->id,
    ]);
  });

  it('can send a new message in a chat', function () {
    $messageData = [
      'content' => 'This is a test message.',
    ];

    $this->messageService->sendMessage($this->chat, $messageData);

    $this->assertDatabaseHas('messages', [
      'chat_id' => $this->chat->id,
      'sender_id' => $this->authUser->id,
      'content' => 'This is a test message.',
    ]);
  });

  it('broadcasts an event when a message is sent', function () {
    Event::fake();

    $messageData = [
      'content' => 'This is a test message.',
    ];

    $this->messageService->sendMessage($this->chat, $messageData);
    $message = Message::where('content', 'This is a test message.')->first();

    Event::assertDispatched(GotMessage::class, function ($event) use ($message) {
      return $event->message->id === $message->id;
    });
  });
});
