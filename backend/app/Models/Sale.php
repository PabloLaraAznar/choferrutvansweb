<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $table = "sales";

    protected $primaryKey = 'id';

    protected $fillable = [
        'folio',
        'id_user',
        'id_payment',
        'id_route_unit_schedule',
        'id_rate',
        'data',
        'status',
    ];

}
