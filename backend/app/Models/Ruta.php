<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruta extends Model
{
    protected $table = "routes";

    protected $primaryKey = 'id';

    protected $fillable = [
    
    'id_location_s',
    'id_location_f',

    ];


   public function ubicacionInicio()
{
    return $this->belongsTo(Localidad::class, 'id_location_s');
}

public function ubicacionFinal()
{
    return $this->belongsTo(Localidad::class, 'id_location_f');
}


    public function shipment()
    {

        return $this->HasMany(Envio::class, 'id');
    }

    // public function fare()
    // {

    //     return $this->belongsTo(Tarifa::class, 'origin_locality_id', 'id');
    // }

}