<?php

use App\Models\Chirp;
use App\Models\User;
use App\Services\ChirpService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

describe('ChirpService', function () {
  beforeEach(function () {
    $this->chirpService = app(ChirpService::class);
    $this->user = User::factory()->create();
    Auth::login($this->user);
  });

  it('can create a new chirp', function () {
    $chirpData = [
      'message' => 'This is a test chirp.',
    ];

    $chirp = $this->chirpService->createChirp($this->user, $chirpData);

    expect($chirp)->toBeInstanceOf(Chirp::class);
    expect($chirp->user_id)->toBe($this->user->id);
    expect($chirp->message)->toBe('This is a test chirp.');
    $this->assertDatabaseHas('chirps', [
      'user_id' => $this->user->id,
      'message' => 'This is a test chirp.',
    ]);
  });

  it('can retrieve chirps for a user', function () {
    Chirp::factory(3)->create(['user_id' => $this->user->id]);

    $chirps = $this->chirpService->getAllChirpsWithLikes($this->user->id);

    expect($chirps)->toHaveCount(3);
    expect($chirps->first())->toBeInstanceOf(Chirp::class);
  });

  it('can update an existing chirp', function () {
    $chirp = Chirp::factory()->create([
      'user_id' => $this->user->id,
      'message' => 'Old message.',
    ]);

    $updatedData = ['message' => 'Updated message.'];

    $this->chirpService->updateChirp($chirp, $updatedData);
    $this->assertDatabaseHas('chirps', [
      'id' => $chirp->id,
      'message' => 'Updated message.',
    ]);
  });

  it('can delete a chirp', function () {
    $chirp = Chirp::factory()->create(['user_id' => $this->user->id]);

    $this->chirpService->deleteChirp($chirp);

    $this->assertDatabaseMissing('chirps', ['id' => $chirp->id]);
  });
});
