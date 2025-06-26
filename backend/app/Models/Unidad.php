<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unidad extends Model
{
    protected $table = "units";

    protected $primaryKey = 'id';

    protected $fillable = [
        'plate',
        'capacitance',
        'brand',
        'model',
        'year',
        

    ];

    public function route_unit()
    {

        return $this->hasMany(RutaUnidad::class,'id');
    }

}