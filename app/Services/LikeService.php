<?php

namespace App\Services;

use App\Models\Chirp;
use Illuminate\Support\Facades\Auth;

class LikeService
{
  /**
   * Toggle the like status for a chirp by a user.
   */
  public function toggleLike(Chirp $chirp)
  {
    $userId = Auth::id();
    $existingLike = $chirp->likes()->where('user_id', $userId)->first();

    if ($existingLike) {
      $existingLike->delete();
      $status = 'unliked';
    } else {
      $chirp->likes()->create(['user_id' => $userId]);
      $status = 'liked';
    }

    return ['status' => $status, 'likes_count' => $chirp->likes()->count()];
  }
}