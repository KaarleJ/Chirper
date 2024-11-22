<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ChatController extends Controller
{
    /**
     * Display a list of chats for the authenticated user.
     */
    public function index()
    {
        $user = User::find(Auth::user()->id);

        $follows = User::whereHas('followers', function ($query) use ($user) {
            $query->where('follower_id', $user->id);
        })->get();

        $chats = Chat::where('user_one_id', $user->id)
            ->orWhere('user_two_id', $user->id)
            ->with([
                'userOne',
                'userTwo',
                'messages' => function ($query) {
                    $query->latest()->first();
                }
            ])
            ->get();

        return Inertia::render('Chats/Index', [
            'chats' => $chats,
            'follows' => $follows,
        ]);
    }

    /**
     * Display messages for a specific chat.
     */
    public function show(Chat $chat)
    {
        $userId = Auth::user()->id;

        if ($chat->user_one_id !== $userId && $chat->user_two_id !== $userId) {
            abort(403, 'Unauthorized access to this chat.');
        }

        $chat->load(['userOne', 'userTwo']);

        $messages = $chat->messages()->orderBy('created_at', 'asc')->get();

        $chats = Chat::with([
            'userOne',
            'userTwo',
            'messages' => function ($query) {
                $query->latest()->first();
            }
        ])
            ->where('user_one_id', $userId)
            ->orWhere('user_two_id', $userId)
            ->get();

        $followings = User::whereHas('followers', function ($query) use ($userId) {
            $query->where('follower_id', $userId);
        })->get();

        return inertia('Chats/Index', [
            'chats' => $chats,
            'follows' => $followings,
            'messages' => $messages,
            'currentChat' => $chat,
        ]);
    }

    /**
     * Create or get a chat between two users.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::find(Auth::user()->id);
        $otherUserId = $request->user_id;

        Chat::firstOrCreate(
            [
                'user_one_id' => min($user->id, $otherUserId),
                'user_two_id' => max($user->id, $otherUserId),
            ]
        );

        return redirect(route('chats.index'));
    }
}
