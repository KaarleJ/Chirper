<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\AuthService;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
  protected AuthService $authService;

  public function __construct(AuthService $authService)
  {
    $this->authService = $authService;
  }

  /**
   * Display the login view.
   */
  public function create(): Response
  {
    return Inertia::render('Auth/Login', [
      'canResetPassword' => Route::has('password.request'),
      'status' => session('status'),
    ]);
  }

  /**
   * Handle an incoming authentication request.
   */
  public function store(LoginRequest $request): RedirectResponse
  {
    $this->authService->login($request);
    return redirect()->intended(route('home', absolute: false));
  }

  /**
   * Destroy an authenticated session.
   */
  public function destroy(): RedirectResponse
  {
    $this->authService->logout();
    return redirect('/');
  }
}
