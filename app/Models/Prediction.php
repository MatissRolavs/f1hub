<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prediction extends Model
{
    protected $fillable = [
        'user_id', 'race_id', 'predicted_positions', 'points_awarded'
    ];

    protected $casts = [
        'predicted_positions' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function race()
    {
        return $this->belongsTo(Race::class);
    }
}
