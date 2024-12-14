<?php

namespace App\Services;

use App\Models\User;

class UserService
{
  /**
   * Get a list of users followed by the given user.
   */
  public function getUsersFollowed(int $userId)
  {
    return User::whereHas('followers', function ($query) use ($userId) {
      $query->where('follower_id', $userId);
    })->get();
  }
}