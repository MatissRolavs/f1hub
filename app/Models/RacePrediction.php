<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RacePrediction extends Model
{
    //
    protected $fillable = [
        'user_id',
        'season',
        'round',
        'predicted_order',
    ];
    public function user()
{
    return $this->belongsTo(User::class);
}
}

