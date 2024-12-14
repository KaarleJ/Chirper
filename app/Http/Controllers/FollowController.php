<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\FollowService;

class FollowController extends Controller
{
  protected FollowService $followService;

  public function __construct(FollowService $followService)
  {
    $this->followService = $followService;
  }

  /**
   * Follow a user.
   */
  public function follow(User $user)
  {
    $this->followService->followUser($user);
    return back()->with('success', 'You are now following ' . $user->name);
  }

  /**
   * Unfollow a user.
   */
  public function unfollow(User $user)
  {
    $this->followService->unfollowUser($user);
    return back()->with('success', 'You have unfollowed ' . $user->name);
  }
}
