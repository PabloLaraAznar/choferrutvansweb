<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'freight_id',
        'sender_name',
        'sender_address',
        'receiver_name',
        'receiver_address',
        'tracking_number',
        'status',
        'shipped_at',
        'delivered_at',
        'site_id'
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'status' => 'string'
    ];

    // Relación con Site
    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    // Relación con Freight
    public function freight()
    {
        return $this->belongsTo(Freight::class);
    }

    // Scope para filtrar por site
    public function scopeBySite($query, $siteId)
    {
        return $query->where('site_id', $siteId);
    }

    // Scope por estado
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope para envíos pendientes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Scope para envíos entregados
    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }
}
