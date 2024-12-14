<?php

namespace App\Services;

use App\Models\Chirp;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class ChirpService
{
  /**
   * Retrieve all chirps with user and like information.
   */
  public function getAllChirpsWithLikes(int $userId)
  {
    return Chirp::with('user:id,username,profile_picture,name')
      ->withCount('likes')
      ->orderBy('created_at', 'desc')
      ->get()
      ->map(function ($chirp) use ($userId) {
        $chirp->setAttribute('liked', $chirp->likes()->where('user_id', $userId)->exists());
        return $chirp;
      });
  }

  /**
   * Create a new chirp.
   */
  public function createChirp(User $user, array $data): Chirp
  {
    return $user->chirps()->create($data);
  }

  /**
   * Get details for a specific chirp.
   */
  public function getChirpDetails(Chirp $chirp, int $userId): Chirp
  {
    $chirp->loadCount('likes');
    $chirp->setAttribute('liked', $chirp->likes()->where('user_id', $userId)->exists());
    return $chirp->load([
      'user:id,username,profile_picture,name',
      'comments.user:id,username,profile_picture,name',
    ]);
  }

  /**
   * Update a chirp.
   */
  public function updateChirp(Chirp $chirp, array $data): void
  {
    Gate::authorize('update', $chirp);
    $chirp->update($data);
  }

  /**
   * Delete a chirp.
   */
  public function deleteChirp(Chirp $chirp): void
  {
    Gate::authorize('delete', $chirp);
    $chirp->delete();
  }
}
