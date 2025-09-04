<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
     protected $fillable = [
        'driver_id',
        'code',
        'permanent_number',
        'given_name',
        'family_name',
        'date_of_birth',
        'nationality',
        'url',
    ];
    public function standings()
{
    return $this->hasMany(Standing::class);
}

public function latestStanding()
{
    return $this->hasOne(Standing::class)->latestOfMany();
}

}
