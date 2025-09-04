<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Standing extends Model
{
    protected $fillable = [
        'driver_id',
        'constructor_id',
        'season',
        'round',
        'position',
        'points',
        'wins',
    ];
    public function constructor()
{
    return $this->belongsTo(Constructor::class);
}

}
