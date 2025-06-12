<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = ['plate', 'capacity', 'photo'];

    // app/Models/Unit.php
    public function drivers()
    {
        return $this->belongsToMany(Driver::class, 'driver_unit', 'id_unit', 'id_driver')
                    ->withPivot('status')
                    ->withTimestamps();
    }

}
