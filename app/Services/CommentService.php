<?php

namespace App\Services;

use App\Models\Chirp;
use App\Models\Comment;
use Illuminate\Support\Facades\Gate;

class CommentService
{
  /**
   * Create a new comment on a chirp.
   */
  public function createComment(Chirp $chirp, int $userId, array $data): Comment
  {
    return $chirp->comments()->create(
      array_merge($data, ['user_id' => $userId])
    );
  }

  /**
   * Delete a specific comment.
   */
  public function deleteComment(Comment $comment): void
  {
    Gate::authorize('delete', $comment);
    $comment->delete();
  }
}