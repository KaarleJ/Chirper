<?php

use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

describe('UserService', function () {
  beforeEach(function () {
    $this->userService = app(UserService::class);
    $this->authUser = User::factory()->create([
      'name' => 'Auth User',
    ]);
    Auth::login($this->authUser);
  });

  it('can retrieve a list of users followed by a given user', function () {
    $followedUsers = User::factory(3)->create();

    foreach ($followedUsers as $followedUser) {
      $followedUser->followers()->attach($this->authUser->id);
    }

    $results = $this->userService->getUsersFollowed($this->authUser->id);

    expect($results)->toHaveCount(3);
    expect($results->pluck('id')->sort()->values())->toEqual($followedUsers->pluck('id')->sort()->values());
  });

  it('returns an empty collection if the user follows no one', function () {
    $results = $this->userService->getUsersFollowed($this->authUser->id);

    expect($results)->toHaveCount(0);
  });
});
