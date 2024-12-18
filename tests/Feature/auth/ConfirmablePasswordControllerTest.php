<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

uses(RefreshDatabase::class);

describe('ConfirmablePasswordController', function () {
  beforeEach(function () {
    $this->user = User::factory()->create([
      'password' => bcrypt('password123'),
    ]);
    Auth::login($this->user);
  });

  it('can confirm the user password', function () {
    $response = $this->post(route('password.confirm'), [
      'password' => 'password123',
    ]);

    $response->assertRedirect('/home');
    $response->assertSessionHasNoErrors();
  });

  it('cannot confirm with an invalid password', function () {
    $response = $this->post(route('password.confirm'), [
      'password' => 'wrongpassword',
    ]);

    $response->assertSessionHasErrors(['password']);
  });
});
