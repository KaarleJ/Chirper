<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\AuthService;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Auth\ConfirmPasswordRequest;
use Inertia\Inertia;
use Inertia\Response;

class ConfirmablePasswordController extends Controller
{
  protected AuthService $authService;

  public function __construct(AuthService $authService)
  {
    $this->authService = $authService;
  }
  /**
   * Show the confirm password view.
   */
  public function show(): Response
  {
    return Inertia::render('Auth/ConfirmPassword');
  }

  /**
   * Confirm the user's password.
   */
  public function store(ConfirmPasswordRequest $request): RedirectResponse
  {
    $this->authService->confirmPassword($request);
    return redirect()->intended(route('home', absolute: false));
  }
}
