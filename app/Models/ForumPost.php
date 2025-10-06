<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumPost extends Model
{

    protected $fillable = ['race_id', 'user_id', 'title', 'body'];

    public function race()
    {
        return $this->belongsTo(Race::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    public function isLikedBy(User $user)
{
    return $this->likes()->where('user_id', $user->id)->exists();
}
}
