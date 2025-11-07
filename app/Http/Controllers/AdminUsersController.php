<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminUsersController extends Controller
{
    public function index(){
        $users = User::with('threads')
                ->orderByDesc('created_at')
                ->get();
        return inertia('admin/Users/Index', ['users' => $users]);
    }

    public function destroy(User $user){
        $user->delete();
        return back();
    }
}
