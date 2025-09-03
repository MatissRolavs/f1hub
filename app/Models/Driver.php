<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable = [
        'first_name', 'last_name', 'code', 'dob', 'nationality',
        'constructor_id', 'photo_url'
    ];

    public function constructor()
    {
        return $this->belongsTo(Constructor::class);
    }

    public function raceResults()
    {
        return $this->hasMany(RaceResult::class);
    }
}
