<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\AuthService;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
  protected AuthService $authService;

  public function __construct(AuthService $authService)
  {
    $this->authService = $authService;
  }

  /**
   * Mark the authenticated user's email address as verified.
   */
  public function __invoke(EmailVerificationRequest $request): RedirectResponse
  {
    if ($this->authService->hasVerifiedEmail($request->user())) {
      return redirect()->intended(route('home', absolute: false) . '?verified=1');
    }

    if ($this->authService->markEmailAsVerified($request->user())) {
      event(new Verified($request->user()));
    }

    return redirect()->intended(route('home', absolute: false) . '?verified=1');
  }
}
