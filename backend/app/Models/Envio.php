<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Envio extends Model
{
    protected $table = "shipments";

    protected $primaryKey = 'id';

    protected $fillable = [
        'sender_name',
        'receiver_name',
        'total',
        'photo',
        'description',
        'route_unit_id',
        'schedule_id',
        'route_id',
    ];

    public function route()
    {
        return $this->belongsTo(Ruta::class, 'route_id', 'id');
    }
    public function schedules()
    {
        return $this->belongsTo(Horario::class, 'schedule_id', 'id');
    }
    public function route_unit()
    {
        return $this->belongsTo(RutaUnidad::class, 'route_unit_id', 'id');
    }
}

