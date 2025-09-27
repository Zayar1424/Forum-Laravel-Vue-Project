<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $guarded = [];
    // a reply belongs to a thread
    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    // a reply belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
