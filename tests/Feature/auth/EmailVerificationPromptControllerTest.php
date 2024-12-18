<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Inertia\Testing\AssertableInertia;

uses(RefreshDatabase::class);

describe('EmailVerificationPromptController', function () {
  beforeEach(function () {
    $this->user = User::factory()->create([
      'email_verified_at' => null,
    ]);
    Auth::login($this->user);
  });

  it('shows the email verification prompt for unverified users', function () {
    $response = $this->get(route('verification.notice'));

    $response->assertStatus(200);
    $response->assertInertia(
      fn(AssertableInertia $page) =>
      $page->component('Auth/VerifyEmail')
    );
  });

  it('redirects verified users away from the prompt', function () {
    $this->user->markEmailAsVerified();

    $response = $this->get(route('verification.notice'));

    $response->assertRedirect('/home');
  });
});
