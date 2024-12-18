<?php

use App\Models\Like;
use App\Models\User;
use App\Models\Chirp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

describe('Like Model', function () {
  it('can retrieve the user who liked a chirp', function () {
    $user = User::factory()->create();
    $chirp = Chirp::factory()->create();
    $like = Like::factory()->create([
      'user_id' => $user->id,
      'chirp_id' => $chirp->id,
    ]);

    expect($like->user->id)->toBe($user->id);
  });

  it('can retrieve the chirp that was liked', function () {
    $user = User::factory()->create();
    $chirp = Chirp::factory()->create();
    $like = Like::factory()->create([
      'user_id' => $user->id,
      'chirp_id' => $chirp->id,
    ]);

    expect($like->chirp->id)->toBe($chirp->id);
  });
});
