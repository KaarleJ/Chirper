<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\RedirectResponse;

class SocialAuthService
{
  /**
   * Redirect to the social provider.
   */
  public function redirectToProvider(string $provider): RedirectResponse
  {
    $this->validateProvider($provider);
    return Socialite::driver($provider)->redirect();
  }

  /**
   * Handle the callback from the social provider.
   */
  public function handleProviderCallback(string $provider): void
  {
    $this->validateProvider($provider);

    $socialUser = Socialite::driver($provider)->user();

    $user = User::firstOrCreate(
      ['email' => $socialUser->email],
      [
        'name' => $this->getName($socialUser->name),
        'username' => $socialUser->nickname ?? $this->generateUsername($socialUser->name),
        'is_social' => true,
        "{$provider}_id" => $socialUser->id,
        "{$provider}_token" => $socialUser->token,
        "{$provider}_refresh_token" => $socialUser->refreshToken,
        'profile_picture' => $socialUser->getAvatar(),
      ]
    );

    $user->update([
      "{$provider}_id" => $socialUser->id,
      "{$provider}_token" => $socialUser->token,
      "{$provider}_refresh_token" => $socialUser->refreshToken,
      'profile_picture' => $socialUser->getAvatar(),
      'is_social' => true,
    ]);

    Auth::login($user);
  }

  /**
   * Validate the social provider.
   */
  protected function validateProvider(string $provider): void
  {
    if (!in_array($provider, ['github', 'google'])) {
      abort(404);
    }
  }

  /**
   * Generate a username if none is provided.
   */
  protected function generateUsername(string $name): string
  {
    $nameParts = explode(' ', $name);
    return count($nameParts) > 2 ? str_replace(['“', '”'], '', $nameParts[1]) : $nameParts[0];
  }

  /**
   * Get the first name + lastname from the name that the provider provided.
   */
  protected function getName(string $name): string
  {
    $nameParts = explode(' ', $name);
    return count($nameParts) > 2 ? $nameParts[0] . ' ' . $nameParts[2] : $name;
  }
}
