<?php

use App\Models\Comment;
use App\Models\User;
use App\Models\Chirp;
use App\Services\CommentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

describe('CommentService', function () {
  beforeEach(function () {
    $this->commentService = app(CommentService::class);
    $this->user = User::factory()->create();
    $this->chirp = Chirp::factory()->create();
    Auth::login($this->user);
  });

  it('can create a new comment', function () {
    $commentData = [
      'content' => 'This is a test comment.',
    ];

    $comment = $this->commentService->createComment($this->chirp, $this->user->id, $commentData);

    expect($comment)->toBeInstanceOf(Comment::class);
    expect($comment->user_id)->toBe($this->user->id);
    expect($comment->chirp_id)->toBe($this->chirp->id);
    expect($comment->content)->toBe('This is a test comment.');
    $this->assertDatabaseHas('comments', [
      'user_id' => $this->user->id,
      'chirp_id' => $this->chirp->id,
      'content' => 'This is a test comment.',
    ]);
  });

  it('can delete a comment', function () {
    $comment = Comment::factory()->create([
      'user_id' => $this->user->id,
      'chirp_id' => $this->chirp->id,
    ]);

    $this->commentService->deleteComment($comment);

    $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
  });
});
