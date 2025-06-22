<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RouteUnitSchedule extends Model
{
    protected $table = 'route_unit_schedule';

    protected $fillable = [
        'id_route_unit',
        'schedule_date',
        'schedule_time',
        'status',
    ];

    public function routeUnit()
    {
        return $this->belongsTo(RouteUnit::class, 'id_route_unit');
    }

    public function getStartAttribute()
    {
        return $this->schedule_date . 'T' . $this->schedule_time;
    }
}
