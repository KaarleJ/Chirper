<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

describe('PasswordController', function () {
  beforeEach(function () {
    $this->user = User::factory()->create([
      'password' => bcrypt('oldpassword123'),
    ]);
    $this->actingAs($this->user);
  });

  it('can update the user\'s password with valid data', function () {
    $response = $this->put(route('password.update'), [
      'current_password' => 'oldpassword123',
      'password' => 'newpassword123',
      'password_confirmation' => 'newpassword123',
    ]);

    $response->assertRedirect();

    $this->user->refresh();
    $this->assertTrue(Hash::check('newpassword123', $this->user->password));
  });

  it('cannot update the password with incorrect current password', function () {
    $response = $this->put(route('password.update'), [
      'current_password' => 'wrongpassword',
      'password' => 'newpassword123',
      'password_confirmation' => 'newpassword123',
    ]);

    $response->assertSessionHasErrors(['current_password']);

    $this->user->refresh();
    $this->assertTrue(Hash::check('oldpassword123', $this->user->password));
  });

  it('cannot update the password if the new passwords do not match', function () {
    $response = $this->put(route('password.update'), [
      'current_password' => 'oldpassword123',
      'password' => 'newpassword123',
      'password_confirmation' => 'mismatchedpassword',
    ]);

    $response->assertSessionHasErrors(['password']);

    $this->user->refresh();
    $this->assertTrue(Hash::check('oldpassword123', $this->user->password));
  });
});
