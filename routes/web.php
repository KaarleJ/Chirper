<?php

use App\Http\Controllers\ChirpController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('home');
    }

    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

Route::get('/auth/redirect/{provider}', [SocialAuthController::class, 'redirect']);
Route::get('/auth/callback/{provider}', [SocialAuthController::class, 'callback']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/home', [ChirpController::class, 'index'])->name('home');
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/request-delete', [ProfileController::class, 'requestDelete'])->name('profile.requestDelete');

    Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/{user}/follow', [FollowController::class, 'follow'])->name('profile.follow');
    Route::post('/profile/{user}/unfollow', [FollowController::class, 'unfollow'])->name('profile.unfollow');
});

Route::get('/profile/confirm-delete/{userId}', [ProfileController::class, 'confirmDelete'])->middleware('signed')->name('profile.confirmDelete');



Route::resource('chirps', ChirpController::class)
    ->only(['index', 'store', 'update', 'destroy'])
    ->middleware(['auth', 'verified']);

Route::resource('chats', ChatController::class)
    ->only(['index', 'store'])
    ->middleware(['auth', 'verified']);

Route::resource('messages', ChatController::class)
    ->only(['index', 'store'])
    ->middleware(['auth', 'verified']);

require __DIR__ . '/auth.php';
