<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\NewPasswordRequest;
use App\Services\Auth\PasswordService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class NewPasswordController extends Controller
{
  protected PasswordService $passwordService;

  public function __construct(PasswordService $passwordService)
  {
    $this->passwordService = $passwordService;
  }

  /**
   * Display the password reset view.
   */
  public function create(Request $request): Response
  {
    return Inertia::render('Auth/ResetPassword', [
      'email' => $request->email,
      'token' => $request->route('token'),
    ]);
  }

  /**
   * Handle an incoming new password request.
   */
  public function store(NewPasswordRequest $request): RedirectResponse
  {
    $this->passwordService->resetPassword($request->validated());
    return redirect()->route('login')->with('status', __('passwords.reset'));
  }
}
