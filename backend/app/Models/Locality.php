<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Locality extends Model
{
    protected $table = 'localities';

    protected $fillable = [
        'longitude',
        'latitude',
        'locality',
        'street',
        'postal_code',
        'municipality',
        'state',
        'country',
        'locality_type',
    ];
}
