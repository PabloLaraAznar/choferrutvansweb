<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TravelHistory extends Model
{
    protected $table = 'travel_history';

    protected $fillable = [
        'id_sale',
        'id_route_unit_schedule',
        'status',
        'actual_departure',
        'actual_arrival',
        'passenger_rating',
        'report',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'status' => 'string',
        'actual_departure' => 'datetime',
        'actual_arrival' => 'datetime',
        'passenger_rating' => 'integer',
        'report' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class, 'id_sale');
    }

    public function routeUnitSchedule(): BelongsTo
    {
        return $this->belongsTo(RouteUnitSchedule::class, 'id_route_unit_schedule');
    }
}