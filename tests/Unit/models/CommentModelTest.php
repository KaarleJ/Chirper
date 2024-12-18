<?php

use App\Models\Comment;
use App\Models\User;
use App\Models\Chirp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

describe('Comment Model', function () {
  it('can retrieve the user who posted the comment', function () {
    $user = User::factory()->create();
    $chirp = Chirp::factory()->create();
    $comment = Comment::factory()->create([
      'user_id' => $user->id,
      'chirp_id' => $chirp->id,
    ]);

    expect($comment->user->id)->toBe($user->id);
  });

  it('can retrieve the chirp the comment belongs to', function () {
    $user = User::factory()->create();
    $chirp = Chirp::factory()->create();
    $comment = Comment::factory()->create([
      'user_id' => $user->id,
      'chirp_id' => $chirp->id,
    ]);

    expect($comment->chirp->id)->toBe($chirp->id);
  });
});
