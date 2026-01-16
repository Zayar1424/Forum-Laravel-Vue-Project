<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // a user has many threads
    public function threads()
    {
        return $this->hasMany(Thread::class);
    }

    // a user has many replies
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    // a user belongsToMany followers
    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id')->withTimestamps();
    }

    // a user belongsToMany followings
    public function following()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id')->withTimestamps();
    }

    // follow function
    public function follow($userID)
    {
        return $this->followers()->attach($userID);
    }

    // unfollow
    public function unfollow($userID)
    {
        return $this->following()->detach($userID);
    }

    // check if this user follows given user id
    public function isFollowing($userID): bool
    {
        return $this->following()->where('users.id', $userID)->exists();
    }
}
