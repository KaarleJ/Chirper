<?php

namespace App\Services\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ConfirmPasswordRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\Verified;


class AuthService
{
  /**
   * Handle user login.
   */
  public function login(LoginRequest $request): void
  {
    $request->authenticate();
    session()->regenerate();
  }

  /**
   * Handle user logout.
   */
  public function logout(): void
  {
    Auth::guard('web')->logout();

    session()->invalidate();
    session()->regenerateToken();
  }

  /**
   * Confirm the user's password.
   */
  public function confirmPassword(ConfirmPasswordRequest $request): void
  {
    if (
      !Auth::guard('web')->validate([
        'email' => $request->user()->email,
        'password' => $request->password(),
      ])
    ) {
      throw ValidationException::withMessages([
        'password' => __('auth.password'),
      ]);
    }

    $request->session()->put('auth.password_confirmed_at', time());
  }

  /**
   * Check if the user's email is verified.
   */
  public function hasVerifiedEmail(User $user): bool
  {
    return $user->hasVerifiedEmail();
  }

  /**
   * Send email verification notification.
   */
  public function sendEmailVerificationNotification(User $user): void
  {
    $user->sendEmailVerificationNotification();
  }

  /**
   * Mark the user's email as verified.
   */
  public function markEmailAsVerified(User $user): bool
  {
    if (!$user->hasVerifiedEmail()) {
      $user->markEmailAsVerified();
      event(new Verified($user));
      return true;
    }

    return false;
  }
}
