<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

uses(RefreshDatabase::class);

describe('AuthenticatedSessionController', function () {
  it('can log in a user', function () {
    $user = User::factory()->create([
      'password' => bcrypt('password123'),
    ]);

    $response = $this->post(route('login'), [
      'email' => $user->email,
      'password' => 'password123',
    ]);

    $response->assertRedirect('/home');
    $this->assertAuthenticatedAs($user);
  });

  it('cannot log in with invalid credentials', function () {
    $user = User::factory()->create([
      'password' => bcrypt('password123'),
    ]);

    $response = $this->post(route('login'), [
      'email' => $user->email,
      'password' => 'wrongpassword',
    ]);

    $response->assertSessionHasErrors(['email']);
    $this->assertGuest();
  });

  it('can log out an authenticated user', function () {
    $user = User::factory()->create();
    Auth::login($user);

    $response = $this->post(route('logout'));

    $response->assertRedirect('/');
    $this->assertGuest();
  });
});
