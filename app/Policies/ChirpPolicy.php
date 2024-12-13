<?php

namespace App\Policies;

use App\Models\Chirp;
use App\Models\User;

class ChirpPolicy
{
  /**
   * Determine whether the user can update the model.
   */
  public function update(User $user, Chirp $chirp)
  {
    return $chirp->user()->is($user);
  }

  /**
   * Determine whether the user can delete the model.
   */
  public function delete(User $user, Chirp $chirp)
  {
    return $this->update($user, $chirp);
  }
}
