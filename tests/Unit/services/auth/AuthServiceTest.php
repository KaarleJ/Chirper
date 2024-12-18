<?php

use App\Http\Requests\Auth\ConfirmPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Services\Auth\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\Verified;
use Mockery\MockInterface;
use Tests\TestCase;
use Illuminate\Auth\Notifications\VerifyEmail;

uses(TestCase::class, RefreshDatabase::class);

describe('AuthService', function () {
  beforeEach(function () {
    $this->authService = app(AuthService::class);
    $this->user = User::factory()->create([
      'email' => 'test@example.com',
    ]);
    Auth::login($this->user);
  });

  it('can handle user login', function () {
    $mockRequest = $this->mock(LoginRequest::class, function (MockInterface $mock) {
      $mock->shouldReceive('authenticate')->once();
    });

    $this->authService->login($mockRequest);

    expect(Auth::check())->toBeTrue();
  });

  it('can handle user logout', function () {
    Auth::login($this->user);

    $this->authService->logout();

    expect(Auth::check())->toBeFalse();
  });

  it('can confirm the user\'s password', function () {
    $mockRequest = $this->mock(ConfirmPasswordRequest::class, function (MockInterface $mock) {
      $mock->shouldReceive('user')->andReturn($this->user);
      $mock->shouldReceive('password')->andReturn('password');
      $mock->shouldReceive('session->put')->with('auth.password_confirmed_at', Mockery::type('int'))->once();
    });

    $this->authService->confirmPassword($mockRequest);
  });

  it('throws an exception if password confirmation fails', function () {
    $mockRequest = $this->mock(ConfirmPasswordRequest::class, function (MockInterface $mock) {
      $mock->shouldReceive('user')->andReturn($this->user);
      $mock->shouldReceive('password')->andReturn('wrongpassword');
    });

    Auth::shouldReceive('guard->validate')->with([
      'email' => $this->user->email,
      'password' => 'wrongpassword',
    ])->andReturnFalse();

    $this->expectException(ValidationException::class);

    $this->authService->confirmPassword($mockRequest);
  });

  it('checks if the user has verified their email', function () {
    $this->user->email_verified_at = now();

    $isVerified = $this->authService->hasVerifiedEmail($this->user);

    expect($isVerified)->toBeTrue();
  });

  it('sends an email verification notification', function () {
    Notification::fake();

    $this->authService->sendEmailVerificationNotification($this->user);

    Notification::assertSentTo($this->user, VerifyEmail::class);
  });

  it('marks the user\'s email as verified', function () {
    Event::fake();

    $this->user->email_verified_at = null;

    $result = $this->authService->markEmailAsVerified($this->user);

    expect($result)->toBeTrue();
    expect($this->user->hasVerifiedEmail())->toBeTrue();

    Event::assertDispatched(Verified::class);
  });

  it('does not mark the email as verified if already verified', function () {
    $this->user->email_verified_at = now();

    $result = $this->authService->markEmailAsVerified($this->user);

    expect($result)->toBeFalse();
  });
});
