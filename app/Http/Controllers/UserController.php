<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function show(User $user) {
        return Inertia::render('Profile/Show', [
            'user' => $user->load('threads.replies', 'replies.thread', 'followers'),
            'followed' => $user->followers->contains(Auth::id()),
            'backUrl' => url()->previous(),
        ]);
    }
}
