<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Inertia\Testing\AssertableInertia;

uses(RefreshDatabase::class);

describe('ProfileController', function () {
  beforeEach(function () {
    $this->user = User::factory()->create();
    Auth::login($this->user);
  });

  it('can display the profile of a user', function () {
    $response = $this->get(route('profile.show', $this->user->id));

    $response->assertStatus(200);
    $response->assertInertia(
      fn(AssertableInertia $page) =>
      $page->component('Profile/Show')
        ->where('user', $this->user->only('id', 'name', 'username', 'profile_picture'))
        ->where('is_following', false)
        ->where('followers', 0)
        ->where('followings', 0)
        ->where('chirps', [])
    );
  });

  it('can update the profile of the authenticated user', function () {
    $updateData = [
      'username' => 'UpdatedName',
      'email' => $this->user->email,
    ];

    $response = $this->patch(route('profile.update'), $updateData);

    $response->assertRedirect();
    $this->assertDatabaseHas('users', [
      'id' => $this->user->id,
      'username' => 'UpdatedName',
    ]);
  });

  it('can display the profile edit form', function () {
    $response = $this->get(route('profile.edit'));

    $response->assertStatus(200);
    $response->assertInertia(
      fn(AssertableInertia $page) =>
      $page->component('Profile/Edit')
    );
  });

  it('can request account deletion', function () {
    $response = $this->post(route('profile.requestDelete'));

    $response->assertRedirect();
    $response->assertSessionHas('status', 'A confirmation link has been sent to your email.');
  });

  it('can confirm account deletion', function () {
    $signedUrl = URL::signedRoute('profile.confirmDelete', ['user' => $this->user->id]);

    $response = $this->get($signedUrl);

    $response->assertRedirect('/');
    $response->assertSessionHas('status', 'Your account has been successfully deleted.');
    $this->assertDatabaseMissing('users', ['id' => $this->user->id]);
  });
});
