<?php

use App\Models\Chat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Inertia\Testing\AssertableInertia;

uses(RefreshDatabase::class);

describe('ChatController', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
        Auth::login($this->user);

        $this->chats = collect(range(1, 5))->map(function () {
            $otherUser = User::factory()->create();
            return Chat::factory()->create([
                'user_one_id' => $this->user->id,
                'user_two_id' => $otherUser->id,
            ]);
        });
    });

    it('can display a list of chats for the authenticated user', function () {
        $response = $this->get(route('chats.index'));

        $response->assertStatus(200);
        $response->assertInertia(
            fn(AssertableInertia $page) =>
            $page->component('Chats/Index')
                ->has('chats', 5)
        );
    });

    it('can create a new chat', function () {
        $userTwo = User::factory()->create();

        $chatData = [
            'user_id' => $userTwo->id,
        ];

        $response = $this->post(route('chats.store'), $chatData);

        $chat = Chat::where('user_one_id', $this->user->id)
            ->where('user_two_id', $userTwo->id)
            ->firstOrFail();

        $response->assertRedirect(route('chats.show', $chat));
        $this->assertDatabaseHas('chats', [
            'user_one_id' => $this->user->id,
            'user_two_id' => $userTwo->id,
        ]);
    });

    it('can display a specific chat', function () {
        $chat = $this->chats->first();

        $response = $this->get(route('chats.show', $chat));

        $response->assertStatus(200);
        $response->assertInertia(
            fn(AssertableInertia $page) =>
            $page->component('Chats/Show')
                ->has('currentChat')
        );
    });

    it('can mark messages in a chat as read', function () {
        $chat = $this->chats->first();

        $response = $this->postJson(route('messages.markAsRead', $chat));

        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);
    });
});
