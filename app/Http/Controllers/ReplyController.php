<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    public function destroy(Reply $reply){
        $thread = $reply->thread;   // grab parent thread before deleting
        $reply->delete();

        return to_route('threads.show', $thread);
    }

    public function update(Reply $reply){
        request()->validate([
            'body' => 'required|string|max:1000',
        ]);

        $reply->body = request('body');
        $reply->save();

        return to_route('threads.show', $reply->thread);
    }
}
