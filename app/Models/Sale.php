<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    protected $table = 'sales';

    protected $fillable = [
        'folio',
        'id_user',
        'id_payment',
        'id_route_unit_schedule',
        'id_rate',
        'data',
        'status',
        'amount',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'data' => 'array',
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function routeUnitSchedule(): BelongsTo
    {
        return $this->belongsTo(RouteUnitSchedule::class, 'id_route_unit_schedule');
    }
}