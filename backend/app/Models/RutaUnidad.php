<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RutaUnidad extends Model
{
    //

    protected $table = "route_unit";

    protected $primaryKey = 'id';

   protected $fillable = [
    'id_route',
    'id_driver_unit',
    'intermediate_location_id',
    'price',
    'created_at',
    'updated_at',
];



    public function driver()
    {

        return $this->hasMany(Conductor::class, 'id');
    }


    public function route()
    {

        return $this->belongsTo(Ruta::class, 'route_id', 'id');
    }

    public function unit()
    {

        return $this->belongsTo(Unidad::class, 'unit_id', 'id');
    }

    public function schedule()
    {

        return $this->belongsTo(Horario::class, 'schedule_id', 'id');
    }

    public function shipment()
    {

        return $this->HasMany(Envio::class, 'id');
    }

    public function ruta()
    {
        return $this->belongsTo(Ruta::class, 'id_route');
    }

    public function driverUnit()
    {
        return $this->belongsTo(DriverUnit::class, 'id_driver_unit');
    }
}
