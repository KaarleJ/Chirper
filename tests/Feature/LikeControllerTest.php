<?php

use App\Models\Chirp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

uses(RefreshDatabase::class);

describe('LikeController', function () {
  beforeEach(function () {
    $this->user = User::factory()->create();
    $this->chirp = Chirp::factory()->create();
    Auth::login($this->user);
  });

  it('can toggle like on a chirp', function () {
    $response = $this->post(route('chirps.like', $this->chirp->id));

    $response->assertStatus(200);
    $response->assertJson(['status' => "liked"]);
    $this->assertDatabaseHas('likes', [
      'user_id' => $this->user->id,
      'chirp_id' => $this->chirp->id,
    ]);

    $response = $this->post(route('chirps.like', $this->chirp->id));

    $response->assertStatus(200);
    $response->assertJson(['status' => "unliked"]);
    $this->assertDatabaseMissing('likes', [
      'user_id' => $this->user->id,
      'chirp_id' => $this->chirp->id,
    ]);
  });
});
