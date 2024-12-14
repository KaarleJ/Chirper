<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Exception;

class FollowService
{
  /**
   * Follow a user.
   */
  public function followUser(User $targetUser): void
  {
    if (Auth::id() === $targetUser->id) {
      throw new Exception('You cannot follow yourself.');
    }

    $authUser = User::findOrFail(Auth::id());

    if (!$authUser->isFollowing($targetUser)) {
      $authUser->followings()->attach($targetUser);
    }
  }

  /**
   * Unfollow a user.
   */
  public function unfollowUser(User $targetUser): void
  {
    $authUser = User::findOrFail(Auth::id());

    if ($authUser->isFollowing($targetUser)) {
      $authUser->followings()->detach($targetUser);
    }
  }
}