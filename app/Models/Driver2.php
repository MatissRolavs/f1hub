<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    //
    protected $fillable = [
        'driver_id',
        'given_name',
        'family_name',
        'nationality',
        'permanent_number',
        'url',
    ];
}
