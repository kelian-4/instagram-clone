<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReelController;
// ─── Routes publiques ─────────────────────────────────────────────────────────
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('feed')
        : redirect()->route('login');
});

// ─── Routes authentifiées ─────────────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Feed
    Route::get('/feed', [PostController::class, 'index'])->name('feed');
    Route::get('/reels', [ReelController::class, 'index'])->name('reels.index')->middleware('auth');
    
    // Posts
    Route::get('/posts/create',   [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts',         [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}',   [PostController::class, 'show'])->name('posts.show');
    Route::delete('/posts/{post}',[PostController::class, 'destroy'])->name('posts.destroy');

    // Likes (JSON pour Livewire/Alpine)
    Route::post('/posts/{post}/like', [LikeController::class, 'toggle'])->name('posts.like');

    // Commentaires
    Route::post('/posts/{post}/comments',      [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}',        [CommentController::class, 'destroy'])->name('comments.destroy');
    // Bookmarks
    Route::post('/posts/{post}/bookmark', [BookmarkController::class, 'toggle'])->name('posts.bookmark');
    Route::get('/saved',                  [BookmarkController::class, 'index'])->name('bookmarks.index');

    // Stories
    Route::post('/stories',             [StoryController::class, 'store'])->name('stories.store');
    Route::get('/stories/{story}',      [StoryController::class, 'show'])->name('stories.show');
    Route::delete('/stories/{story}',   [StoryController::class, 'destroy'])->name('stories.destroy');

    // Profil
    Route::get('/profile/edit',          [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile',               [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/{username}', [ProfileController::class, 'show'])
   	 ->name('profile.show')
   	 ->where('username', '^(?!login|logout|register|feed|posts|profile|explore|messages|notifications|saved|stories|follow|comments|password|email)[a-zA-Z0-9_.]+$');
    // Follow
    Route::post('/follow/{user}', [FollowController::class, 'toggle'])->name('follow.toggle');

    // Explore
    Route::get('/explore', [ExploreController::class, 'index'])->name('explore');

    // Notifications
    Route::get('/notifications',          [NotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/read-all',[NotificationController::class, 'markAllRead'])->name('notifications.read-all');

    // Messages
    Route::get('/messages',                  [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}',           [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{conversation}',  [MessageController::class, 'store'])->name('messages.store');
    Route::post('/messages/{conversation}/accept',  [MessageController::class, 'acceptRequest'])->name('messages.accept');
    Route::post('/messages/{conversation}/decline', [MessageController::class, 'declineRequest'])->name('messages.decline');
    Route::post('/messages/{conversation}/block',   [MessageController::class, 'blockRequest'])->name('messages.block');
Route::post('/messages/{conversation}/move',    [MessageController::class, 'moveFolder'])->name('messages.move');
 
    // Partage
    Route::post('/share-post',         [MessageController::class, 'sharePost'])->name('posts.share');
    Route::post('/share-to-story/{post}', [StoryController::class, 'sharePost'])->name('story.share-post');
    Route::post('/repost/{post}',      [PostController::class, 'repost'])->name('posts.repost');

});

// ─── Auth (Breeze) ─────────────────────────────────────────────────────────────
require __DIR__.'/auth.php';
