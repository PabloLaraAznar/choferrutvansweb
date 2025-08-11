<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Route extends Model
{
    protected $table = 'routes';

    protected $fillable = [
        'id_location_s',
        'id_location_f',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function origin(): BelongsTo
    {
        return $this->belongsTo(Locality::class, 'id_location_s');
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Locality::class, 'id_location_f');
    }
}