<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;

class AdminUsersController extends Controller
{
    public function index(){
        $users = User::with('threads')
                ->orderByDesc('created_at')
                ->get();
        return inertia('admin/Users/Index', ['users' => $users]);
    }

    public function userForm(){
        return inertia('admin/Users/UserForm');
    }

    public function store(){
        $data = request()->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:0,1',
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'is_admin' => (bool)$data['role'],
            'password' => Hash::make($data['password']),
        ]);

        return redirect()->route('admin.users');
    }

    public function edit(User $user){
        return inertia('admin/Users/UserForm', ['user' => $user]);
    }

    public function update(User $user){
        $data = request()->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:0,1',
        ]);

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'is_admin' => (bool)$data['role'],
        ]);

        if ($data['password']) {
            $user->update(['password' => Hash::make($data['password'])]);
        }

        return redirect()->route('admin.users');
    }

    public function destroy(User $user){
        $user->delete();
        return back();
    }
}
