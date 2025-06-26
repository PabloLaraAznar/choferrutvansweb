<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    protected $table = 'routes';

    protected $fillable = [
        'id_location_s',
        'id_location_f',
    ];

    public function locationStart()
    {
        return $this->belongsTo(Locality::class, 'id_location_s');
    }

    public function locationEnd()
    {
        return $this->belongsTo(Locality::class, 'id_location_f');
    }
}
