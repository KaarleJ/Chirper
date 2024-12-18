<?php

use App\Models\User;
use App\Models\Chirp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

uses(RefreshDatabase::class);

describe('SearchController', function () {
  beforeEach(function () {
    $this->user = User::factory()->create();
    $this->otherUsers = User::factory(5)->create();
    $this->chirps = Chirp::factory(5)->create();
    Auth::login($this->user);
  });

  it('can search for users by name or username', function () {
    $searchTerm = $this->otherUsers->first()->name;

    $response = $this->get(route('search.get', ['query' => $searchTerm, 'strategy' => 'people']));

    $response->assertStatus(200);
    $response->assertJsonCount(1);
    $response->assertJsonFragment(['name' => $searchTerm]);
  });

  it('can search for chirps by content', function () {
    $searchTerm = $this->chirps->first()->message;

    $response = $this->get(route('search.get', ['query' => $searchTerm, 'strategy' => 'chirps']));

    $response->assertStatus(200);
    $response->assertJsonCount(1);
    $response->assertJsonFragment(['message' => $searchTerm]
    );
  });

  it('returns no results for a non-matching search term', function () {
    $searchTerm = 'NonExistentQuery';

    $response = $this->get(route('search.get', ['query' => $searchTerm, 'strategy' => 'people']));

    $response->assertStatus(200);
    $response->assertJsonCount(0);

    $response = $this->get(route('search.get', ['query' => $searchTerm, 'strategy' => 'chirps']));

    $response->assertStatus(200);
    $response->assertJsonCount(0);
  });
});
