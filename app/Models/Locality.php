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
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'longitude' => 'decimal:6',
        'latitude' => 'decimal:6',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}