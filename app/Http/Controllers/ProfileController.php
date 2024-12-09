<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\AccountDeletionMail;
use Illuminate\Support\Facades\URL;

class ProfileController extends Controller
{

    /*
     * Display the user's profile.
     */
    public function show(User $user)
    {
        $is_following = $user->followers()->where('follower_id', Auth::id())->exists();
        $followers = $user->followers()->count();
        $followings = $user->followings()->count();
        return Inertia::render('Profile/Show', [
            'user' => $user->only('id', 'name', 'username', 'profile_picture'),
            'chirps' => $user->chirps()
                ->with('user:id,username,profile_picture,name')
                ->withCount('likes')
                ->latest()
                ->get()
                ->map(function ($chirp) {
                    $chirp->setAttribute('liked', $chirp->likes()->where('user_id', Auth::id())->exists());
                    return $chirp;
                }),
            'is_following' => $is_following,
            'followers' => $followers,
            'followings' => $followings,
        ]);
    }


    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function requestDelete()
    {
        $user = Auth::user();

        // Generate a signed URL for account deletion
        $deletionUrl = URL::temporarySignedRoute(
            'profile.confirmDelete',
            now()->addMinutes(30),
            ['userId' => $user->id]
        );

        // Send the confirmation email
        Mail::to($user->email)->send(new AccountDeletionMail($deletionUrl));

        return back()->with('status', 'A confirmation link has been sent to your email.');
    }

    public function confirmDelete($userId)
    {
        $user = User::where('id', $userId)->first();
        $user->delete();

        return redirect('/')->with('status', 'Your account has been successfully deleted.');
    }
}
