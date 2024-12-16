<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EmailVerificationPromptController extends Controller
{
  protected AuthService $authService;

  public function __construct(AuthService $authService)
  {
    $this->authService = $authService;
  }

  /**
   * Display the email verification prompt.
   */
  public function __invoke(Request $request): RedirectResponse|Response
  {
    return $this->authService->hasVerifiedEmail($request->user())
      ? redirect()->intended(route('home', absolute: false))
      : Inertia::render('Auth/VerifyEmail', ['status' => session('status')]);
  }
}
