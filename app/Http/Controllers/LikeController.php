<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use Illuminate\Support\Facades\Auth;


class LikeController extends Controller
{
    /**
     * Toggle like on a chirp.
     */
    public function toggle(Chirp $chirp)
    {
        $user = Auth::user();

        $existingLike = $chirp->likes()->where('user_id', $user->id)->first();

        if ($existingLike) {
            $existingLike->delete();
            $status = 'unliked';
        } else {
            $chirp->likes()->create(['user_id' => $user->id]);
            $status = 'liked';
        }

        return response()->json(['status' => $status, 'likes_count' => $chirp->likes()->count()]);
    }
}
