<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Services\Auth\PasswordService;
use App\Http\Requests\Auth\PasswordResetLinkRequest;
use Inertia\Inertia;
use Inertia\Response;

class PasswordResetLinkController extends Controller
{
  protected PasswordService $passwordService;

  public function __construct(PasswordService $passwordService)
  {
    $this->passwordService = $passwordService;
  }

  /**
   * Display the password reset link request view.
   */
  public function create(): Response
  {
    return Inertia::render('Auth/ForgotPassword', [
      'status' => session('status'),
    ]);
  }

  /**
   * Handle an incoming password reset link request.
   */
  public function store(PasswordResetLinkRequest $request): RedirectResponse
  {
    $this->passwordService->sendResetLink($request->validated());
    return back()->with('status', __('passwords.sent'));
  }
}
