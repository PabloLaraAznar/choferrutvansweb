<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Freight extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'weight',
        'dimensions',
        'value',
        'status',
        'site_id'
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'value' => 'decimal:2',
        'dimensions' => 'array',
        'status' => 'string'
    ];

    // Relación con Site
    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    // Relación con shipments
    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }

    // Scope para filtrar por site
    public function scopeBySite($query, $siteId)
    {
        return $query->where('site_id', $siteId);
    }

    // Scope para freight activo
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
