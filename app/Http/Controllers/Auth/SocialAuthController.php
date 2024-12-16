<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\SocialAuthService;
use Illuminate\Http\RedirectResponse;

class SocialAuthController extends Controller
{
  protected SocialAuthService $socialAuthService;

  public function __construct(SocialAuthService $socialAuthService)
  {
    $this->socialAuthService = $socialAuthService;
  }

  /**
   * Redirect to the social provider.
   */
  public function redirect(string $provider): RedirectResponse
  {
    return $this->socialAuthService->redirectToProvider($provider);
  }

  /**
   * Handle the callback from the social provider.
   */
  public function callback(string $provider): RedirectResponse
  {
    $this->socialAuthService->handleProviderCallback($provider);
    return redirect('/home');
  }
}
