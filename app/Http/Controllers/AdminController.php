<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard() {
        $userCounts = User::count();
        $threadCounts = Thread::count();
        $replyCounts = Reply::count();
        $categoryCounts = Category::count();

        $latestUsers = User::latest()->take(5)->get();
        $latestThreads = Thread::with('user', 'replies')
                        ->latest()->take(5)->get();

        return inertia('admin/Dashboard', [
            'stats' => [
                'users' => $userCounts,
                'threads' => $threadCounts,
                'replies' => $replyCounts,
                'categories' => $categoryCounts,
            ],
            'latestUsers' => $latestUsers,
            'latestThreads' => $latestThreads,
        ]);
    }
}
