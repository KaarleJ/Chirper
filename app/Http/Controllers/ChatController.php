<?php

namespace App\Http\Controllers;

use App\Services\ChatService;
use App\Models\Chat;
use App\Http\Requests\ChatRequest;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ChatController extends Controller
{
  protected ChatService $chatService;

  public function __construct(ChatService $chatService)
  {
    $this->chatService = $chatService;
  }

  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $chats = $this->chatService->getUserChats(Auth::id());
    return Inertia::render('Chats/Index', $chats);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(ChatRequest $request)
  {
    $chat = $this->chatService->createChat(Auth::id(), $request->validated());
    return redirect()->route('chats.show', $chat);
  }

  /**
   * Display the specified resource.
   */
  public function show(Chat $chat)
  {
    $chatDetails = $this->chatService->getChatDetails($chat);
    return Inertia::render('Chats/Show', $chatDetails);
  }

  /**
   * Mark messages in the chat as read.
   */
  public function markAsRead(Chat $chat)
  {
    $status = $this->chatService->markMessagesAsRead($chat);
    return response()->json(["status" => $status]);
  }
}
