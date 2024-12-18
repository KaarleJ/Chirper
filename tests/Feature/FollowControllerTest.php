<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

uses(RefreshDatabase::class);

describe('FollowController', function () {
  beforeEach(function () {
    $this->user = User::factory()->create();
    $this->otherUser = User::factory()->create();
    Auth::login($this->user);
  });

  it('can follow another user', function () {
    $response = $this->post(route('profile.follow', $this->otherUser->id));

    $response->assertRedirect();
    $this->assertDatabaseHas('follows', [
      'follower_id' => $this->user->id,
      'following_id' => $this->otherUser->id,
    ]);
  });

  it('can unfollow a user', function () {
    $this->user->followings()->attach($this->otherUser->id);

    $response = $this->post(route('profile.unfollow', $this->otherUser->id));
    $response->assertRedirect();
    $this->assertDatabaseMissing('follows', [
      'follower_id' => $this->user->id,
      'following_id' => $this->otherUser->id,
    ]);
  });
});
