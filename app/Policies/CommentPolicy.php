<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Comment $comment)
    {
        return $comment->user()->is($user);
    }
}