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
        'name' => $socialUser->name,
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
    return strtolower(str_replace(' ', '_', $name)) . '_' . uniqid();
  }
}
