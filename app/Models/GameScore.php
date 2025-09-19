<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameScore extends Model
{
    protected $fillable = [
        'race_id', 'race_name', 'player_name', 'score', 'total'
    ];
}

?>