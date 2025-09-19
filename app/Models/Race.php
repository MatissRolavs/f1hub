<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Race extends Model
{
    //

    protected $fillable = [
        'season',
        'round',
        'name',
        'date',
        'time',
        'circuit_name',
        'locality',
        'country',
        'url',
        'track_image', 'track_length', 'turns', 'lap_record', 'description',
    ];
    public function forumPosts()
{
    return $this->hasMany(\App\Models\ForumPost::class);
}

public function results()
{
    return $this->hasMany(Race_result::class, 'round', 'round')
                ->where('season', $this->season);
}

}
