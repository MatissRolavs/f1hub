<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Race_result extends Model
{
    //
    protected $table = 'race_results';

    protected $fillable = [
        'season',
        'round',
        'race_name',
        'date',
        'time',
        'driver_id',
        'constructor_id',
        'grid',
        'position',
        'position_text',
        'points',
        'laps',
        'status',
        'race_time',
        'fastest_lap_time',
        'fastest_lap_rank',
        'fastest_lap_speed',
    ];
    public function race()
{
    return $this->belongsTo(\App\Models\Race::class, 'round', 'round');
}
public function driver()
{
    return $this->belongsTo(Driver::class);
}
}
