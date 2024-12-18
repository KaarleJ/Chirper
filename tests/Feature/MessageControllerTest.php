<?php

use App\Models\Chat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

uses(RefreshDatabase::class);

describe('MessageController', function () {
  beforeEach(function () {
    $this->user = User::factory()->create();
    $this->chat = Chat::factory()->create([
      'user_one_id' => $this->user->id,
      'user_two_id' => User::factory()->create()->id,
    ]);
    Auth::login($this->user);
  });

  it('can send a new message in a specific chat', function () {
    $messageData = [
      'content' => 'This is a test message.',
    ];

    $response = $this->post(route('messages.store', $this->chat->id), $messageData);

    $response->assertStatus(200);
    $this->assertDatabaseHas('messages', [
      'chat_id' => $this->chat->id,
      'sender_id' => $this->user->id,
      'content' => 'This is a test message.',
    ]);
  });
});
