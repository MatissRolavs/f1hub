<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Race extends Model
{
    protected $fillable = [
        'name', 'circuit', 'location', 'country', 'date', 'round'
    ];

    public function results()
    {
        return $this->hasMany(RaceResult::class);
    }

    public function predictions()
    {
        return $this->hasMany(Prediction::class);
    }
}
