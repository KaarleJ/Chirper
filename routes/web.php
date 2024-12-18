<?php

use App\Http\Controllers\ChirpController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Landing routes
Route::get('/', function () {
  return Auth::check()
    ? redirect()->route('home')
    : Inertia::render('Welcome', [
      'canLogin' => Route::has('login'),
      'canRegister' => Route::has('register'),
    ]);
});

// Social authentication routes
Route::get('/auth/redirect/{provider}', [SocialAuthController::class, 'redirect']);
Route::get('/auth/callback/{provider}', [SocialAuthController::class, 'callback']);


// Public routes
Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');
Route::get('/chirps/{chirp}', [ChirpController::class, 'show'])->name('chirps.show');
Route::get('/profile/confirm-delete/{user}', [ProfileController::class, 'confirmDelete'])->middleware('signed')->name('profile.confirmDelete');

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
  // Home route
  Route::get('/home', [ChirpController::class, 'index'])->name('home');

  // Profile routes
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::post('/profile/request-delete', [ProfileController::class, 'requestDelete'])->name('profile.requestDelete');

  // Comment routes
  Route::post('/chirps/{chirp}/comments', [CommentController::class, 'store'])->name('comments.store');
  Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

  // Search routes
  Route::get('/search', [SearchController::class, 'index'])->name('search.index');
  Route::get('/search/get', [SearchController::class, 'search'])->name('search.get');

  // Like route
  Route::post('/chirps/{chirp}/like', [LikeController::class, 'toggle'])->name('chirps.like');

  // Follow routes
  Route::post('/profile/{user}/follow', [FollowController::class, 'follow'])->name('profile.follow');
  Route::post('/profile/{user}/unfollow', [FollowController::class, 'unfollow'])->name('profile.unfollow');

  // Chat routes
  Route::post('/chats/{chat}/messages', [MessageController::class, 'store'])->name('messages.store');
  Route::post('/chats/{chat}/mark-as-read', [ChatController::class, 'markAsRead'])->name('messages.markAsRead');

  // Chirp resource
  Route::resource('chirps', ChirpController::class)
    ->only(['index', 'store', 'update', 'destroy']);

  // Chat resource
  Route::resource('chats', ChatController::class)
    ->only(['index', 'show', 'store']);
});


// Signed routes
Route::middleware('signed')->group(function () {
  // Delete profile route
  Route::get('/profile/confirm-delete/{user}', [ProfileController::class, 'confirmDelete'])->name('profile.confirmDelete');
});

require __DIR__ . '/auth.php';
