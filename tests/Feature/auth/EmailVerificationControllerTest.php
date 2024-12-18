<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;

uses(RefreshDatabase::class);

describe('EmailVerificationNotificationController', function () {
  beforeEach(function () {
    Notification::fake();
    $this->user = User::factory()->create([
      'email_verified_at' => null,
    ]);
    Auth::login($this->user);
  });

  it('can send an email verification notification', function () {
    $response = $this->post(route('verification.send'));

    $response->assertRedirect('/');
    $response->assertSessionHas('status', 'verification-link-sent');

    Notification::assertSentTo($this->user, VerifyEmail::class);
  });

  it('does not send an email verification notification if already verified', function () {
    $this->user->markEmailAsVerified();

    $response = $this->post(route('verification.send'));

    $response->assertRedirect('/home');
    Notification::assertNothingSent();
  });
});
