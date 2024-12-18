<?php

use App\Models\User;
use App\Models\Chirp;
use App\Services\LikeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

describe('LikeService', function () {
  beforeEach(function () {
    $this->likeService = app(LikeService::class);
    $this->authUser = User::factory()->create();
    Auth::login($this->authUser);
    $this->chirp = Chirp::factory()->create();
  });

  it('can like a chirp', function () {
    $this->likeService->toggleLike($this->chirp);

    $this->assertDatabaseHas('likes', [
      'user_id' => $this->authUser->id,
      'chirp_id' => $this->chirp->id,
    ]);
  });

  it('can unlike a chirp if already liked', function () {
    $this->likeService->toggleLike($this->chirp);
    $this->likeService->toggleLike($this->chirp);

    $this->assertDatabaseMissing('likes', [
      'user_id' => $this->authUser->id,
      'chirp_id' => $this->chirp->id,
    ]);
  });

  it('does not create duplicate likes', function () {
    $this->likeService->toggleLike($this->chirp);
    $this->likeService->toggleLike($this->chirp);

    $this->assertDatabaseCount('likes', 0);
  });
});
