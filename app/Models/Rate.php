<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rate extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_service',
        'rate_type',
        'rate_cost',
        'status',
        'site_id'
    ];

    protected $casts = [
        'rate_cost' => 'decimal:2',
        'status' => 'string'
    ];

    // Relación con Site
    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    // Relación con Service
    public function service()
    {
        return $this->belongsTo(Service::class, 'id_service');
    }

    // Relación con sales
    public function sales()
    {
        return $this->hasMany(Sale::class, 'id_rate');
    }

    // Scope para filtrar por site
    public function scopeBySite($query, $siteId)
    {
        return $query->where('site_id', $siteId);
    }

    // Scope para rates activos
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope por tipo de tarifa
    public function scopeByType($query, $type)
    {
        return $query->where('rate_type', $type);
    }
}
