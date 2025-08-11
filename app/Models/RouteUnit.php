<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RouteUnit extends Model
{
    protected $table = 'route_unit';

    protected $fillable = [
        'id_route',
        'id_driver_unit',
        'intermediate_location_id',
        'price',
    ];

    public function schedules()
    {
        return $this->hasMany(RouteUnitSchedule::class, 'id_route_unit');
    }
    public function route()
{
    return $this->belongsTo(Route::class, 'id_route');
}


    // Relaciones opcionales si tienes las otras tablas

    public function driverUnit()
    {
        return $this->belongsTo(DriverUnit::class, 'id_driver_unit');
    }


}
