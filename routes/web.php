<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminUsersController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FollowerController;
use Inertia\Inertia;
use App\Models\Thread;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;

Route::get('/', [ThreadController::class, 'index']);

Route::get('/threads/{thread}', [ThreadController::class, 'show'])->name('threads.show');

Route::get('/new-thread', [ThreadController::class, 'newThread'])->name('new-thread');

Route::post('/threads', [ThreadController::class, 'store'])->name('threads.store');

Route::delete('/threads/{thread}', [ThreadController::class, 'destroy'])->name('threads.destroy');

Route::get('/threads/{thread}/edit', [ThreadController::class, 'edit'])->name('threads.edit');

Route::put('/threads/{thread}/update', [ThreadController::class, 'update'])->name('threads.update');

Route::post('/threads/{thread}/reply', [ThreadController::class, 'reply'])->name('threads.reply');

Route::delete('/replies/{reply}', [ReplyController::class, 'destroy'])->name('replies.destroy');

Route::put('/replies/{reply}/update', [ReplyController::class, 'update'])->name('replies.update');

Route::get('/user/{user}', [UserController::class, 'show'])->name('user.show');

Route::post('/user/{user}/follow', [FollowerController::class, 'follow'])->name('user.follow');

Route::delete('/user/{user}/unfollow', [FollowerController::class, 'unfollow'])->name('user.unfollow');

// Admin routes
Route::middleware(AdminMiddleware::class)->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminUsersController::class, 'index'])->name('admin.users');
    Route::delete('/admin/users/{user}', [AdminUsersController::class, 'destroy'])->name('admin.users.destroy');
    Route::get('/admin/categories', [CategoryController::class, 'index'])->name('admin.categories');
    Route::delete('/admin/categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
