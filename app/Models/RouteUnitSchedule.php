<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RouteUnitSchedule extends Model
{
    protected $table = 'route_unit_schedule';

    protected $fillable = [
        'id_route_unit',
        'schedule_date',
        'schedule_time',
        'status',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'schedule_date' => 'date',
        'schedule_time' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function routeUnit(): BelongsTo
    {
        return $this->belongsTo(RouteUnit::class, 'id_route_unit');
    }
}