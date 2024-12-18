<?php

use App\Models\Comment;
use App\Models\Chirp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Inertia\Testing\AssertableInertia;

uses(RefreshDatabase::class);

describe('CommentController', function () {
  beforeEach(function () {
    $this->user = User::factory()->create();
    $this->chirp = Chirp::factory()->create();
    Auth::login($this->user);

    $this->comments = Comment::factory(5)->create([
      'user_id' => $this->user->id,
      'chirp_id' => $this->chirp->id,
    ]);
  });

  it('includes comments and related data in chirp show', function () {
    $response = $this->get(route('chirps.show', $this->chirp->id));

    $response->assertStatus(200);
    $response->assertInertia(
      fn(AssertableInertia $page) =>
      $page->component('Chirps/Show')
        ->has('chirp.comments', 5)
        ->where('chirp.comments.0.id', $this->comments->first()->id)
        ->where('chirp.comments.0.user.id', $this->user->id)
        ->where('chirp.comments.0.user.username', $this->user->username)
        ->where('chirp.comments.0.content', $this->comments->first()->content)
    );
  });

  it('can store a new comment', function () {
    $commentData = [
      'content' => 'This is a test comment.',
    ];

    $response = $this->post(route('comments.store', $this->chirp->id), $commentData);

    $response->assertRedirect(route('chirps.show', $this->chirp->id));
    $this->assertDatabaseHas('comments', [
      'chirp_id' => $this->chirp->id,
      'user_id' => $this->user->id,
      'content' => 'This is a test comment.',
    ]);
  });

  it('can delete a comment', function () {
    $comment = $this->comments->first();

    $response = $this->delete(route('comments.destroy', $comment->id));

    $response->assertRedirect();
    $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
  });
});
