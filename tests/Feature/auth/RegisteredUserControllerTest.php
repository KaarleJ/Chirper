<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('RegisteredUserController', function () {
  it('can register a new user', function () {
    $userData = [
      'name' => 'Test User',
      'username' => 'testuser',
      'email' => 'test@example.com',
      'password' => 'password123',
      'password_confirmation' => 'password123',
    ];

    $response = $this->post(route('register.store'), $userData);

    $response->assertRedirect('/home');

    $this->assertDatabaseHas('users', [
      'name' => 'Test User',
      'username' => 'testuser',
      'email' => 'test@example.com',
    ]);

    $this->assertAuthenticated();
  });

  it('cannot register a user with invalid data', function () {
    $response = $this->post(route('register.store'), [
      'name' => '',
      'email' => 'invalid-email',
      'password' => 'pass',
      'password_confirmation' => 'notmatching',
    ]);

    $response->assertSessionHasErrors(['name', 'email', 'password']);

    $this->assertGuest();
  });
});
