<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\ResetPassword;

uses(RefreshDatabase::class);

describe('PasswordResetLinkController', function () {
  beforeEach(function () {
    Notification::fake();
    $this->user = User::factory()->create([
      'email' => 'test@example.com',
    ]);
  });

  it('can send a password reset link to a valid email', function () {
    $response = $this->post(route('password.email'), [
      'email' => $this->user->email,
    ]);

    $response->assertStatus(302);
    $response->assertSessionHas('status', 'We have emailed your password reset link.');

    Notification::assertSentTo($this->user, ResetPassword::class);
  });

  it('does not send a reset link for an invalid email', function () {
    $response = $this->post(route('password.email'), [
      'email' => 'nonexistent@example.com',
    ]);

    $response->assertStatus(302);
    $response->assertSessionHasErrors(['email']);

    Notification::assertNothingSent();
  });
});

