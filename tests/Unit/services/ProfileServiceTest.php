<?php

use App\Models\User;
use App\Services\ProfileService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\AccountDeletionMail;
use App\Models\Chirp;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

describe('ProfileService', function () {
  beforeEach(function () {
    $this->profileService = app(ProfileService::class);
    $this->authUser = User::factory()->create([
      'email' => 'test@example.com',
    ]);
    Auth::login($this->authUser);
  });

  it('can fetch user profile data', function () {
    Chirp::factory()->count(2)->create([
      'user_id' => $this->authUser->id,
    ]);

    $profileData = $this->profileService->getUserProfileData($this->authUser);

    expect($profileData['user']['id'])->toBe($this->authUser->id);
    expect($profileData['chirps'])->toHaveCount(2);
    expect($profileData['is_following'])->toBeFalse();
  });

  it('can update the user\'s profile', function () {
    $updateData = [
      'name' => 'Updated Name',
      'username' => 'updated_username',
    ];

    $this->profileService->updateUserProfile($updateData);

    $this->authUser->refresh();
    expect($this->authUser->name)->toBe('Updated Name');
    expect($this->authUser->username)->toBe('updated_username');
  });

  it('fetches profile edit data', function () {
    $editData = $this->profileService->getProfileEditData($this->authUser);

    expect($editData['mustVerifyEmail'])->toBeFalse();
    expect($editData['status'])->toBeNull();
  });

  it('sends an account deletion request email', function () {
    Mail::fake();

    $this->profileService->sendAccountDeletionRequest();
    expect(Auth::check())->toBeFalse();

    Mail::assertSent(AccountDeletionMail::class, function ($mail) {
      return $mail->hasTo('test@example.com');
    });

  });

  it('deletes the user\'s account', function () {
    $this->profileService->confirmAccountDeletion($this->authUser);

    $this->assertDatabaseMissing('users', ['id' => $this->authUser->id]);
  });
});
