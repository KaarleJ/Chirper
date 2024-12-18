<?php

use App\Models\Chat;
use App\Models\User;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

describe('Chat Model', function () {
  it('can retrieve the first user in the chat', function () {
    $userOne = User::factory()->create();
    $userTwo = User::factory()->create();
    $chat = Chat::factory()->create([
      'user_one_id' => $userOne->id,
      'user_two_id' => $userTwo->id,
    ]);

    expect($chat->userOne->id)->toBe($userOne->id);
  });

  it('can retrieve the second user in the chat', function () {
    $userOne = User::factory()->create();
    $userTwo = User::factory()->create();
    $chat = Chat::factory()->create([
      'user_one_id' => $userOne->id,
      'user_two_id' => $userTwo->id,
    ]);

    expect($chat->userTwo->id)->toBe($userTwo->id);
  });

  it('can retrieve messages in the chat', function () {
    $chat = Chat::factory()->create();
    Message::factory(3)->create(['chat_id' => $chat->id]);

    expect($chat->messages)->toHaveCount(3);
  });
});
