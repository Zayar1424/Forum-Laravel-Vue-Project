<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowerController extends Controller
{
    public function follow(User $user)
    {
        $user->followers()->attach(Auth::id());
        return back();
    }

    public function unfollow(User $user)
    {
        $user->followers()->detach(Auth::id());
        return back();
    }
}
