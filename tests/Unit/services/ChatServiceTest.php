<?php

use App\Models\Chat;
use App\Models\User;
use App\Services\ChatService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

describe('ChatService', function () {
  beforeEach(function () {
    $this->chatService = app(ChatService::class);
    $this->userOne = User::factory()->create();
    $this->userTwo = User::factory()->create();
    Auth::login($this->userOne);
  });

  it('can create a new chat between two users', function () {
    $chat = $this->chatService->createChat(['user_id' => $this->userTwo->id]);

    expect($chat)->toBeInstanceOf(Chat::class);
    expect($chat->user_one_id)->toBe($this->userOne->id);
    expect($chat->user_two_id)->toBe($this->userTwo->id);
    $this->assertDatabaseHas('chats', [
      'user_one_id' => $this->userOne->id,
      'user_two_id' => $this->userTwo->id,
    ]);
  });

  it('can retrieve chats for a user', function () {
    Chat::factory()->create([
      'user_one_id' => $this->userOne->id,
      'user_two_id' => $this->userTwo->id,
    ]);

    $chats = $this->chatService->getUserChats($this->userOne->id)['chats'];
    expect($chats)->toHaveCount(1);
    expect($chats->first())->toBeInstanceOf(Chat::class);
  });

  it('can retrieve chat details', function () {
    $chat = Chat::factory()->create([
      'user_one_id' => $this->userOne->id,
      'user_two_id' => $this->userTwo->id,
    ]);

    $chatDetails = $this->chatService->getChatDetails($chat);

    expect($chatDetails)->toBeArray();
    expect($chatDetails['currentChat'])->toBe($chat);
    expect($chatDetails['messages'])->toHaveCount(0);
  });

  it('can mark messages as read', function () {
    $chat = Chat::factory()->create([
      'user_one_id' => $this->userOne->id,
      'user_two_id' => $this->userTwo->id,
    ]);

    $status = $this->chatService->markMessagesAsRead($chat);

    expect($status)->toBe('success');
  });
});
