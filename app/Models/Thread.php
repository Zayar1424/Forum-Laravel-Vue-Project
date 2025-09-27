<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    /** @use HasFactory<\Database\Factories\ForumFactory> */
    use HasFactory;

    protected $guarded = [];

    // a thread belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // a thread belongs to a category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // a thread  belongs to many tags
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'tag_thread');
    }

    // a thread has many replies
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    // scope to filter threads by category
    public function scopeFilter($query, $filters)
    {
        $query->when($filters['category'] ?? null, function($query, $category) {
                $query->whereHas('category', function ($query) use ($category) {
                $query->where('slug', $category);
            });
        });

        $query->when($filters['tag'] ?? null, function($query, $tag) {
                $query->whereHas('tags', function ($query) use ($tag) {
                $query->where('tag_id', $tag);
            });
        });

        $query->when($filters['search'] ?? null, function($query, $search) {
                $query->where('title', 'like', "%{$search}%");
            });

        return $query;
    }
}
