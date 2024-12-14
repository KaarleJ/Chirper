<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Services\ProfileService;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
  protected ProfileService $profileService;

  public function __construct(ProfileService $profileService)
  {
    $this->profileService = $profileService;
  }

  /**
   * Display the user's profile.
   */
  public function show(User $user): Response
  {
    $profileData = $this->profileService->getUserProfileData($user);
    return Inertia::render('Profile/Show', $profileData);
  }

  /**
   * Update the user's profile.
   */
  public function update(ProfileUpdateRequest $request)
  {
    $this->profileService->updateUserProfile($request->validated());
    return redirect()->back()->with('success', 'Profile updated successfully.');
  }

  /**
   * Display the user's profile form.
   */
  public function edit(Request $request): Response
  {
    $data = $this->profileService->getProfileEditData($request->user());
    return Inertia::render('Profile/Edit', $data);
  }

  /**
   * Request account deletion.
   */
  public function requestDelete()
  {
    $this->profileService->sendAccountDeletionRequest();
    return back()->with('status', 'A confirmation link has been sent to your email.');
  }

  /**
   * Confirm account deletion.
   */
  public function confirmDelete(User $user)
  {
    $this->profileService->confirmAccountDeletion($user);
    return redirect('/')->with('status', 'Your account has been successfully deleted.');
  }
}
