<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FollowController extends Controller
{
    public function follow(User $user)
    {
        if (!$user) {
            Log::error('Route model binding failed. User not found.');
            return back()->withErrors(['error' => 'User not found.']);
        }
        $authUser = User::find(Auth::user()->id);

        if ($authUser->id === $user->id) {
            return back()->withErrors(['error' => 'You cannot follow yourself.']);
        }

        if (!$authUser->isFollowing($user)) {
            $authUser->followings()->attach($user);
        }

        return back()->with('success', 'You are now following ' . $user->name);
    }

    public function unfollow(User $user)
    {
        $authUser = User::find(Auth::user()->id);

        if ($authUser->isFollowing($user)) {
            $authUser->followings()->detach($user);
        }

        return back()->with('success', 'You have unfollowed ' . $user->name);
    }
}
