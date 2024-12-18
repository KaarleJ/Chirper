<?php

use App\Models\User;
use App\Services\FollowService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

describe('FollowService', function () {
  beforeEach(function () {
    $this->followService = app(FollowService::class);
    $this->authUser = User::factory()->create();
    Auth::login($this->authUser);
    $this->targetUser = User::factory()->create();
  });

  it('can follow a user', function () {
    $this->followService->followUser($this->targetUser);

    $this->assertDatabaseHas('follows', [
      'follower_id' => $this->authUser->id,
      'following_id' => $this->targetUser->id,
    ]);
    expect($this->authUser->isFollowing($this->targetUser))->toBeTrue();
  });

  it('throws an exception when a user tries to follow themselves', function () {
    $this->expectException(Exception::class);
    $this->expectExceptionMessage('You cannot follow yourself.');

    $this->followService->followUser($this->authUser);
  });

  it('does not follow a user if already following', function () {
    $this->authUser->followings()->attach($this->targetUser);

    $this->followService->followUser($this->targetUser);

    $this->assertDatabaseCount('follows', 1);
  });

  it('can unfollow a user', function () {
    $this->authUser->followings()->attach($this->targetUser);

    $this->followService->unfollowUser($this->targetUser);

    $this->assertDatabaseMissing('follows', [
      'follower_id' => $this->authUser->id,
      'following_id' => $this->targetUser->id,
    ]);
    expect($this->authUser->isFollowing($this->targetUser))->toBeFalse();
  });

  it('does nothing if trying to unfollow a user that is not followed', function () {
    $this->followService->unfollowUser($this->targetUser);

    $this->assertDatabaseMissing('follows', [
      'follower_id' => $this->authUser->id,
      'following_id' => $this->targetUser->id,
    ]);
  });
});
