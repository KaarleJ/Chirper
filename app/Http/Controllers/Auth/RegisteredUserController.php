<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\RegistrationService;
use App\Http\Requests\Auth\RegisterUserRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
  protected RegistrationService $registrationService;

  public function __construct(RegistrationService $registrationService)
  {
    $this->registrationService = $registrationService;
  }

  /**
   * Display the registration view.
   */
  public function create(): Response
  {
    return Inertia::render('Auth/Register');
  }

  /**
   * Handle an incoming registration request.
   */
  public function store(RegisterUserRequest $request): RedirectResponse
  {
    $this->registrationService->registerUser($request->validated());
    return redirect(route('home', absolute: false));
  }
}
