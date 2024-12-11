<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Chirp;
use App\Models\Comment;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    /**
     * Store a new comment.
     */
    public function store(Request $request, Chirp $chirp)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $chirp->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        return redirect()->route('chirps.show', $chirp);
    }

    /**
     * Delete a comment.
     */
    public function destroy(Comment $comment)
    {
        Gate::authorize('delete', $comment);

        $comment->delete();

        return redirect()->back()->with('success', 'Comment deleted!');
    }
}
