<?php

namespace App\Services;

use App\Mail\AccountDeletionMail;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class ProfileService
{
  /**
   * Fetch data for a user's profile.
   */
  public function getUserProfileData(User $user): array
  {
    $isFollowing = $user->followers()->where('follower_id', Auth::id())->exists();
    $followersCount = $user->followers()->count();
    $followingsCount = $user->followings()->count();
    $chirps = $user->chirps()
      ->with('user:id,username,profile_picture,name')
      ->withCount('likes')
      ->latest()
      ->get();

    return [
      'user' => $user->only('id', 'name', 'username', 'profile_picture'),
      'is_following' => $isFollowing,
      'followers' => $followersCount,
      'followings' => $followingsCount,
      'chirps' => $chirps,
    ];
  }

  /**
   * Update a user's profile.
   */
  public function updateUserProfile(array $data): void
  {
    $user = User::findOrFail(Auth::id());
    $user->update($data);
  }

  /**
   * Fetch profile edit data.
   */
  public function getProfileEditData(User $user): array
  {
    return [
      'mustVerifyEmail' => $user instanceof MustVerifyEmail,
      'status' => session('status'),
    ];
  }

  /**
   * Send account deletion request email.
   */
  public function sendAccountDeletionRequest(): void
  {
    $user = Auth::user();
    $deletionUrl = URL::temporarySignedRoute(
      'profile.confirmDelete',
      now()->addMinutes(30),
      ['userId' => $user->id]
    );

    Mail::to($user->email)->send(new AccountDeletionMail($deletionUrl));
  }

  /**
   * Confirm and delete account.
   */
  public function confirmAccountDeletion(User $user): void
  {
    $user->delete();
  }
}