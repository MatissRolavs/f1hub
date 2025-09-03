<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Constructor extends Model
{
    protected $fillable = ['name', 'nationality', 'logo_url'];

    public function drivers()
    {
        return $this->hasMany(Driver::class);
    }
}
