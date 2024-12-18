<?php

use App\Models\User;
use App\Models\Chirp;
use App\Models\Like;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

describe('User Model', function () {
  it('can check if a user is following another user', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $user->followings()->attach($otherUser->id);

    expect($user->isFollowing($otherUser))->toBeTrue();
    expect($otherUser->isFollowedBy($user))->toBeTrue();
  });

  it('returns false if a user is not following another user', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    expect($user->isFollowing($otherUser))->toBeFalse();
    expect($otherUser->isFollowedBy($user))->toBeFalse();
  });

  it('can retrieve a user\'s chirps', function () {
    $user = User::factory()->create();
    Chirp::factory(3)->create(['user_id' => $user->id]);

    expect($user->chirps)->toHaveCount(3);
  });

  it('can retrieve a user\'s likes', function () {
    $user = User::factory()->create();
    Like::factory(5)->create(['user_id' => $user->id]);

    expect($user->likes)->toHaveCount(5);
  });

  it('can cast attributes correctly', function () {
    $user = User::factory()->create([
      'email_verified_at' => now(),
    ]);

    expect($user->email_verified_at)->toBeInstanceOf(\Carbon\Carbon::class);
  });
});
