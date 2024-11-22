<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class MessageController extends Controller
{
    /**
     * Display messages for a specific chat.
     */
    public function index($chatId)
    {
        $user = User::find(Auth::user()->id);

        $chat = Chat::where('id', $chatId)
            ->where(function ($query) use ($user) {
                $query->where('user_one_id', $user->id)
                    ->orWhere('user_two_id', $user->id);
            })
            ->firstOrFail();

        $messages = $chat->messages()->orderBy('created_at', 'asc')->get();

        return Inertia::render('Messages/Index', [
            'chat' => $chat,
            'messages' => $messages,
        ]);
    }

    /**
     * Send a new message in a specific chat.
     */
    public function store(Request $request, $chatId)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $user = User::find(Auth::user()->id);

        $chat = Chat::where('id', $chatId)
            ->where(function ($query) use ($user) {
                $query->where('user_one_id', $user->id)
                    ->orWhere('user_two_id', $user->id);
            })
            ->firstOrFail();

        $chat->messages()->create([
            'sender_id' => $user->id,
            'content' => $request->content,
        ]);

        return redirect()->route('chats.show', ['chat' => $chat->id]);
    }
}
