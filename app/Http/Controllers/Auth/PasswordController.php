<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UpdatePasswordRequest;
use App\Services\Auth\PasswordService;
use Illuminate\Http\RedirectResponse;

class PasswordController extends Controller
{
  protected PasswordService $passwordService;

  public function __construct(PasswordService $passwordService)
  {
    $this->passwordService = $passwordService;
  }

  /**
   * Update the user's password.
   */
  public function update(UpdatePasswordRequest $request): RedirectResponse
  {
    $this->passwordService->updatePassword($request->user(), $request->validated());

    return back();
  }
}
