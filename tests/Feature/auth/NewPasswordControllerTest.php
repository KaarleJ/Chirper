<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

describe('NewPasswordController', function () {
  beforeEach(function () {
    $this->user = User::factory()->create([
      'email' => 'test@example.com',
    ]);
  });

  it('can reset a user\'s password with a valid token', function () {
    $token = Password::createToken($this->user);

    $response = $this->post(route('password.store'), [
      'email' => $this->user->email,
      'token' => $token,
      'password' => 'newpassword123',
      'password_confirmation' => 'newpassword123',
    ]);

    $response->assertRedirect('/login');

    $this->user->refresh();
    $this->assertTrue(Hash::check('newpassword123', $this->user->password));
  });

  it('cannot reset password with an invalid token', function () {
    $response = $this->post(route('password.store'), [
      'email' => $this->user->email,
      'token' => 'invalid-token',
      'password' => 'newpassword123',
      'password_confirmation' => 'newpassword123',
    ]);

    $response->assertSessionHasErrors(['email']);

    $this->user->refresh();
    $this->assertFalse(Hash::check('newpassword123', $this->user->password));
  });
});
