<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Display a list of chats for the authenticated user.
     */
    public function index()
    {
        $userId = Auth::user()->id;

        $chats = Chat::where('user_one_id', $userId)
            ->orWhere('user_two_id', $userId)
            ->with([
                'userOne',
                'userTwo',
                'messages' => function ($query) {
                    $query->latest()->first();
                }
            ])
            ->get();

        return response()->json($chats);
    }

    /**
     * Create or get a chat between two users.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::where('id', Auth::user()->id);
        $otherUserId = $request->user_id;

        $chat = Chat::firstOrCreate(
            [
                'user_one_id' => min($user->id, $otherUserId),
                'user_two_id' => max($user->id, $otherUserId),
            ]
        );

        return response()->json($chat);
    }
}
