<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RouteUnit extends Model
{
    protected $table = 'route_unit';

    protected $fillable = [
        'id_route',
        'id_driver_unit',
        'intermediate_location_id',
        'price',
        'estimated_duration_seconds',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class, 'id_route');
    }

    public function driverUnit(): BelongsTo
    {
        return $this->belongsTo(DriverUnit::class, 'id_driver_unit');
    }
}