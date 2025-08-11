<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory;

    protected $table = "sales";

    protected $primaryKey = 'id';

    protected $fillable = [
        'folio',
        'id_user',
        'id_payment',
        'id_route_unit_schedule',
        'id_rate',
        'data',
        'status',
        'site_id'
    ];

    protected $casts = [
        'data' => 'array'
    ];

    // Relación con Site
    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    // Relación con User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Relación con Payment
    public function payment()
    {
        return $this->belongsTo(Payment::class, 'id_payment');
    }

    // Relación con RouteUnitSchedule
    public function routeUnitSchedule()
    {
        return $this->belongsTo(RouteUnitSchedule::class, 'id_route_unit_schedule');
    }

    // Relación con Rate
    public function rate()
    {
        return $this->belongsTo(Rate::class, 'id_rate');
    }

    // Scope para filtrar por site
    public function scopeBySite($query, $siteId)
    {
        return $query->where('site_id', $siteId);
    }

    // Scope para filtrar por estado
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
