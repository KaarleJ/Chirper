<?php

use App\Models\User;
use App\Services\Auth\RegistrationService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

describe('RegistrationService', function () {
  beforeEach(function () {
    $this->registrationService = app(RegistrationService::class);
  });

  it('can register a new user', function () {
    Event::fake();

    $userData = [
      'name' => 'John Doe',
      'username' => 'johndoe',
      'email' => 'johndoe@example.com',
      'password' => 'password123',
    ];

    $this->registrationService->registerUser($userData);

    $user = User::where('email', 'johndoe@example.com')->first();

    expect($user)->not->toBeNull();
    expect($user->name)->toBe('John Doe');
    expect($user->username)->toBe('johndoe');
    expect(Hash::check('password123', $user->password))->toBeTrue();

    $this->assertAuthenticatedAs($user);

    Event::assertDispatched(Registered::class, function ($event) use ($user) {
      return $event->user->is($user);
    });
  });
});