<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class PasswordService
{
  /**
   * Update the user's password.
   */
  public function updatePassword(User $user, array $data): void
  {
    if (!Hash::check($data['current_password'], $user->password)) {
      throw ValidationException::withMessages([
        'current_password' => __('auth.password_incorrect'),
      ]);
    }

    $user->update([
      'password' => Hash::make($data['password']),
    ]);
  }

  /**
   * Reset the user's password.
   */
  public function resetPassword(array $data): void
  {
    $status = Password::reset(
      $data,
      function ($user, $password) {
        $user->forceFill([
          'password' => Hash::make($password),
          'remember_token' => Str::random(60),
        ])->save();

        event(new PasswordReset($user));
      }
    );

    if ($status !== Password::PASSWORD_RESET) {
      throw ValidationException::withMessages([
        'email' => [trans($status)],
      ]);
    }
  }

  /**
   * Send a password reset link to the given email.
   */
  public function sendResetLink(array $data): void
  {
    $status = Password::sendResetLink($data);

    if ($status !== Password::RESET_LINK_SENT) {
      throw ValidationException::withMessages([
        'email' => [trans($status)],
      ]);
    }
  }
}