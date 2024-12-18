<?php

use App\Models\User;
use App\Services\Auth\PasswordService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

describe('PasswordService', function () {
  beforeEach(function () {
    $this->passwordService = app(PasswordService::class);
    $this->user = User::factory()->create([
      'password' => Hash::make('currentpassword'),
    ]);
  });

  it('can update the user\'s password', function () {
    $newPasswordData = [
      'current_password' => 'currentpassword',
      'password' => 'newpassword123',
    ];

    $this->passwordService->updatePassword($this->user, $newPasswordData);

    $this->user->refresh();
    expect(Hash::check('newpassword123', $this->user->password))->toBeTrue();
  });

  it('throws an exception if the current password is incorrect', function () {
    $newPasswordData = [
      'current_password' => 'wrongpassword',
      'password' => 'newpassword123',
    ];

    $this->expectException(ValidationException::class);

    $this->passwordService->updatePassword($this->user, $newPasswordData);
  });

  it('can reset the user\'s password', function () {
    Event::fake();

    $resetData = [
      'email' => $this->user->email,
      'password' => 'resetpassword123',
      'password_confirmation' => 'resetpassword123',
      'token' => Password::createToken($this->user),
    ];

    $this->passwordService->resetPassword($resetData);

    $this->user->refresh();
    expect(Hash::check('resetpassword123', $this->user->password))->toBeTrue();

    Event::assertDispatched(PasswordReset::class);
  });

  it('throws an exception if the reset token is invalid', function () {
    $resetData = [
      'email' => $this->user->email,
      'password' => 'resetpassword123',
      'password_confirmation' => 'resetpassword123',
      'token' => 'invalidtoken',
    ];

    $this->expectException(ValidationException::class);

    $this->passwordService->resetPassword($resetData);
  });

  it('can send a password reset link', function () {
    Password::shouldReceive('sendResetLink')
      ->once()
      ->with(['email' => $this->user->email])
      ->andReturn(Password::RESET_LINK_SENT);

    $this->passwordService->sendResetLink(['email' => $this->user->email]);
  });

  it('throws an exception if the reset link cannot be sent', function () {
    Password::shouldReceive('sendResetLink')
      ->once()
      ->with(['email' => $this->user->email])
      ->andReturn(Password::INVALID_USER);

    $this->expectException(ValidationException::class);

    $this->passwordService->sendResetLink(['email' => $this->user->email]);
  });
});
