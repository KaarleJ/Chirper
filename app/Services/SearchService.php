<?php

namespace App\Services;

use App\Models\User;
use App\Models\Chirp;
use Illuminate\Support\Facades\Auth;

class SearchService
{
  /**
   * Perform a search query.
   */
  public function search($query, $strategy)
  {
    $authUserId = Auth::id();
    return $strategy === 'people'
      ? $this->searchUsers($query, $authUserId)
      : $this->searchChirps($query, $authUserId);
  }

  /**
   * Search for users.
   */
  protected function searchUsers($query, $authUserId)
  {
    return User::where('name', 'ilike', "%{$query}%")
      ->withCount([
        'followers as is_following' => function ($query) use ($authUserId) {
          $query->where('follower_id', $authUserId);
        }
      ])
      ->get()
      ->map(function ($user) {
        $user->is_following = $user->is_following > 0;
        return $user;
      });
  }

  /**
   * Search for chirps.
   */
  protected function searchChirps($query, $authUserId)
  {
    return Chirp::where('message', 'ilike', "%{$query}%")
      ->with('user:id,username,profile_picture')
      ->withCount('likes')
      ->get()
      ->map(function ($chirp) use ($authUserId) {
        $chirp->setAttribute('liked', $chirp->likes()->where('user_id', $authUserId)->exists());
        return $chirp;
      });
  }
}