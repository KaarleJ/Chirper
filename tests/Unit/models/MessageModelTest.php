<?php

use App\Models\Message;
use App\Models\User;
use App\Models\Chat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

describe('Message Model', function () {
  it('belongs to a user', function () {
    $user = User::factory()->create();
    $message = Message::factory()->create(['sender_id' => $user->id]);

    expect($message->sender->id)->toBe($user->id);
  });

  it('belongs to a chat', function () {
    $chat = Chat::factory()->create();
    $message = Message::factory()->create(['chat_id' => $chat->id]);

    expect($message->chat->id)->toBe($chat->id);
  });

  it('can retrieve its content', function () {
    $message = Message::factory()->create(['content' => 'This is a test message.']);

    expect($message->content)->toBe('This is a test message.');
  });

  it('has timestamps', function () {
    $message = Message::factory()->create();

    expect($message->created_at)->not->toBeNull();
    expect($message->updated_at)->not->toBeNull();
  });
});
