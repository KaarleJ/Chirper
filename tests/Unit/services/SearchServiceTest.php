<?php

use App\Models\User;
use App\Models\Chirp;
use App\Services\SearchService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

describe('SearchService', function () {
  beforeEach(function () {
    $this->searchService = app(SearchService::class);
    $this->authUser = User::factory()->create([
      'name' => 'Test User',
    ]);
    Auth::login($this->authUser);
  });

  it('can search for users', function () {
    User::factory()->create(['name' => 'John Doe']);
    User::factory()->create(['name' => 'Jane Doe']);
    User::factory()->create(['name' => 'Alice Smith']);

    $results = $this->searchService->search('Doe', 'people');

    expect($results)->toHaveCount(2);
    expect($results->pluck('name'))->toContain('John Doe', 'Jane Doe');
  });

  it('can search for chirps', function () {
    Chirp::factory()->create(['message' => 'Hello World']);
    Chirp::factory()->create(['message' => 'World of Warcraft']);
    Chirp::factory()->create(['message' => 'Goodbye World']);

    $results = $this->searchService->search('World', 'chirps');

    expect($results)->toHaveCount(3);
    expect($results->pluck('message'))->toContain('Hello World', 'World of Warcraft', 'Goodbye World');
  });

  it('returns an empty collection if no users match the query', function () {
    User::factory()->create(['name' => 'John Doe']);

    $results = $this->searchService->search('Nonexistent', 'people');

    expect($results)->toHaveCount(0);
  });

  it('returns an empty collection if no chirps match the query', function () {
    Chirp::factory()->create(['message' => 'Hello World']);

    $results = $this->searchService->search('Nonexistent', 'chirps');

    expect($results)->toHaveCount(0);
  });
});
