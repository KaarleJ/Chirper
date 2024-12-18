<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;

uses(RefreshDatabase::class);

describe('VerifyEmailController', function () {
  beforeEach(function () {
    $this->user = User::factory()->create([
      'email_verified_at' => null,
    ]);
    Auth::login($this->user);
  });

  it('can verify a user\'s email with a valid signed URL', function () {
    $signedUrl = URL::signedRoute('verification.verify', [
      'id' => $this->user->id,
      'hash' => sha1($this->user->email),
    ]);

    $response = $this->get($signedUrl);

    $response->assertRedirect('/home?verified=1');
    $this->user->refresh();
    $this->assertNotNull($this->user->email_verified_at);
  });

  it('does not verify email with an invalid signed URL', function () {
    $invalidUrl = URL::temporarySignedRoute('verification.verify', now()->subMinutes(5), [
      'id' => $this->user->id,
      'hash' => sha1('invalid@example.com'),
    ]);

    $response = $this->get($invalidUrl);

    $response->assertStatus(403);
    $this->user->refresh();
    $this->assertNull($this->user->email_verified_at);
  });
});
