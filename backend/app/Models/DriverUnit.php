<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class DriverUnit extends Pivot
{
    protected $table = 'driver_unit';

    protected $fillable = [
        'id_driver',
        'id_unit',
        'status'
    ];
    public function units()
    {
        return $this->belongsToMany(Unit::class, 'driver_unit', 'id_driver', 'id_unit')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'id_driver');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'id_unit');
    }

    public function routeUnits()
    {
        return $this->hasMany(RouteUnit::class, 'id_driver_unit');
    }

}
