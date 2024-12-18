<?php

use App\Models\Chirp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Inertia\Testing\AssertableInertia;

uses(RefreshDatabase::class);

describe('ChirpController', function () {
  beforeEach(function () {
    $this->user = User::factory()->create();
    Auth::login($this->user);

    $this->chirps = Chirp::factory(5)->create([
      'user_id' => $this->user->id,
    ]);
  });

  it('can display a list of chirps for the authenticated user', function () {
    $response = $this->get(route('chirps.index'));

    $response->assertStatus(200);
    $response->assertInertia(
      fn(AssertableInertia $page) =>
      $page->component('Chirps/Index')
        ->has('chirps', 5)
    );
  });

  it('can display a specific chirp', function () {
    $chirp = $this->chirps->first();

    $response = $this->get(route('chirps.show', $chirp->id));

    $response->assertStatus(200);
    $response->assertInertia(
      fn(AssertableInertia $page) =>
      $page->component('Chirps/Show')
        ->has('chirp')
    );
  });

  it('can create a new chirp', function () {
    $chirpData = [
      'message' => 'This is a test chirp.',
    ];

    $response = $this->post(route('chirps.store'), $chirpData);

    $response->assertRedirect(route('chirps.index'));
    $this->assertDatabaseHas('chirps', [
      'user_id' => $this->user->id,
      'message' => 'This is a test chirp.',
    ]);
  });

  it('can update an existing chirp', function () {
    $chirp = $this->chirps->first();

    $updateData = [
      'message' => 'Updated chirp content.',
    ];

    $response = $this->put(route('chirps.update', $chirp->id), $updateData);

    $response->assertRedirect(route('chirps.index'));
    $this->assertDatabaseHas('chirps', [
      'id' => $chirp->id,
      'message' => 'Updated chirp content.',
    ]);
  });

  it('can delete a chirp', function () {
    $chirp = $this->chirps->first();

    $response = $this->delete(route('chirps.destroy', $chirp->id));

    $response->assertRedirect(route('chirps.index'));
    $this->assertDatabaseMissing('chirps', ['id' => $chirp->id]);
  });
});
