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

Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');
Route::get('/chirps/{chirp}', [ChirpController::class, 'show'])->name('chirps.show');

Route::middleware(['auth', 'verified'])->group(function () {
  Route::get('/home', [ChirpController::class, 'index'])->name('home');
});

Route::middleware('auth')->group(function () {
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::post('/profile/request-delete', [ProfileController::class, 'requestDelete'])->name('profile.requestDelete');

  Route::post('/chirps/{chirp}/comments', [CommentController::class, 'store'])->name('comments.store');
  Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

  Route::get('/search', [SearchController::class, 'index'])->name('search.index');
  Route::get('/search/get', [SearchController::class, 'search'])->name('search.get');

  Route::post('/chirps/{chirp}/like', [LikeController::class, 'toggle'])->name('chirps.like');

  Route::post('/profile/{user}/follow', [FollowController::class, 'follow'])->name('profile.follow');
  Route::post('/profile/{user}/unfollow', [FollowController::class, 'unfollow'])->name('profile.unfollow');

  Route::post('/chats/{chat}/messages', [MessageController::class, 'store'])->name('messages.store');
  Route::post('/chats/{chat}/mark-as-read', [ChatController::class, 'markAsRead'])->name('messages.markAsRead');
});

Route::get('/profile/confirm-delete/{userId}', [ProfileController::class, 'confirmDelete'])->middleware('signed')->name('profile.confirmDelete');

Route::resource('chirps', ChirpController::class)
  ->only(['index', 'store', 'update', 'destroy'])
  ->middleware(['auth', 'verified']);

Route::resource('chats', ChatController::class)
  ->only(['index', 'show', 'store'])
  ->middleware(['auth', 'verified']);

require __DIR__ . '/auth.php';
