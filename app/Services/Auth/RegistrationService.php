<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegistrationService
{
  /**
   * Register a new user.
   */
  public function registerUser(array $data): void
  {
    $user = User::create([
      'name' => $data['name'],
      'username' => $data['username'],
      'email' => $data['email'],
      'password' => Hash::make($data['password']),
    ]);

    event(new Registered($user));

    Auth::login($user);
  }
}