<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
  protected AuthService $authService;

  public function __construct(AuthService $authService)
  {
    $this->authService = $authService;
  }

  /**
   * Send a new email verification notification.
   */
  public function store(Request $request): RedirectResponse
  {
    if ($this->authService->hasVerifiedEmail($request->user())) {
      return redirect()->intended(route('home', absolute: false));
    }

    $this->authService->sendEmailVerificationNotification($request->user());

    return back()->with('status', 'verification-link-sent');
  }
}