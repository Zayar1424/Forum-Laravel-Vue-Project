<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThreadController extends Controller
{
    public function index() {
        $threads=[];
        $threads = Thread::with('category', 'user', 'replies')->filter(request(['category', 'tag', 'search']));
        if(request('filter') === 'most_replied') {
            $threads = $threads->withCount('replies')->orderBy('replies_count', 'desc')->with(['category', 'user', 'replies'])->get();
        } elseif(request('filter') === 'followed_threads' && Auth::check()) {
            $followedIDs = Auth::user()->following->pluck('id');
            $threads = $threads->whereIn('user_id', $followedIDs)->with(['category', 'user', 'replies'])->latest()->get();
        } else {
            $threads = $threads->latest()->get();
        }
        return Inertia::render('Home', [
            'threads' => $threads,
        ]);
    }

    public function show(Thread $thread) {
        $thread->load([
        'category',
        'user',
        'tags',
        'replies' => fn ($q) => $q->latest()->with('user'),
    ]);

    return Inertia::render('threads/Show', [
        'thread' => $thread,
    ]);
    }

    public function newThread() {
        return Inertia::render('threads/ThreadForm');
    }

    public function edit(Thread $thread) {
        return Inertia::render('threads/ThreadForm', [
            'thread' => $thread->load('tags'),
        ]);
    }

    public function store() {

        request()->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'required|exists:tags,id',
        ], [
            'category_id.required' => 'The category field is required.',
            'tags.required' => 'Please select at least one tag.',
        ]);

        $thread = Thread::create([
            'title' => request('title'),
            'body' => request('body'),
            'user_id' => Auth::id(),
            'category_id' => request('category_id'),
        ]);
        if(request('tags')) {
            $thread->tags()->attach(request('tags'));
        }
        return redirect('/');
    }

    public function update(Thread $thread) {
        request()->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'required|exists:tags,id',
        ], [
            'category_id.required' => 'The category field is required.',
            'tags.required' => 'Please select at least one tag.',
        ]);

        $thread->update([
            'title' => request('title'),
            'body' => request('body'),
            'category_id' => request('category_id'),
        ]);
        if(request('tags')) {
            $thread->tags()->sync(request('tags'));
        }
        return redirect('/');
    }

    public function destroy(Thread $thread) {
        $thread->delete();
        return back();
    }

    public function reply(Thread $thread) {
        request()->validate([
            'body' => 'required|string',
        ]);

        $thread->replies()->create([
            'body' => request('body'),
            'user_id' => Auth::id(),
        ]);

        return back();
    }
}
