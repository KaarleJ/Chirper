<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
  public function redirect($provider)
  {
    if (!in_array($provider, ['github', 'google'])) {
      abort(404);
    }
    return Socialite::driver($provider)->redirect();
  }

  public function callback($provider)
  {
    $socialUser = Socialite::driver($provider)->user();

    $user = User::where('email', $socialUser->email)->first();

    $profilePicture = $socialUser->getAvatar();

    if ($user) {
      $user->update([
        "{$provider}_id" => $socialUser->id,
        "{$provider}_token" => $socialUser->token,
        "{$provider}_refresh_token" => $socialUser->refreshToken,
        'profile_picture' => $profilePicture,
        'is_social' => true,
      ]);
    } else {
      $user = User::create([
        'name' => $socialUser->name,
        'username' => $socialUser->nickname,
        'email' => $socialUser->email,
        'is_social' => true,
        "{$provider}_id" => $socialUser->id,
        "{$provider}_token" => $socialUser->token,
        "{$provider}_refresh_token" => $socialUser->refreshToken,
        'profile_picture' => $profilePicture,
      ]);
    }

    Auth::login($user);

    return redirect('/home');
  }
}