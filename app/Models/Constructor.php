<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Constructor extends Model
{
    protected $fillable = [
        'constructor_id',
        'name',
        'nationality',
        'url',
    ];
}
