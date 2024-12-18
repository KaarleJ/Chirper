<?php

use App\Models\Chirp;
use App\Models\User;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

describe('Chirp Model', function () {
  it('can retrieve the user who posted the chirp', function () {
    $user = User::factory()->create();
    $chirp = Chirp::factory()->create([
      'user_id' => $user->id,
    ]);

    expect($chirp->user->id)->toBe($user->id);
  });

  it('can retrieve the comments on the chirp', function () {
    $chirp = Chirp::factory()->create();
    Comment::factory(3)->create(['chirp_id' => $chirp->id]);

    expect($chirp->comments)->toHaveCount(3);
  });

  it('can retrieve the likes on the chirp', function () {
    $chirp = Chirp::factory()->create();
    Like::factory(5)->create(['chirp_id' => $chirp->id]);

    expect($chirp->likes)->toHaveCount(5);
  });
});
