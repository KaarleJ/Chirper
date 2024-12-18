<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Services\CommentService;
use App\Models\Chirp;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    protected CommentService $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    /**
     * Store a new comment.
     */
    public function store(CommentRequest $request, Chirp $chirp)
    {
        $this->commentService->createComment($chirp, Auth::id(), $request->validated());
        return redirect()->route('chirps.show', $chirp);
    }

    /**
     * Delete a comment.
     */
    public function destroy(Comment $comment)
    {
        $this->commentService->deleteComment($comment);
        return redirect()->back()->with('success', 'Comment deleted!');
    }
}
