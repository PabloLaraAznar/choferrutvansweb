<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    // Tabla explícita, aunque Laravel infiere 'drivers' bien
    protected $table = 'drivers';

    // Campos que puedes asignar masivamente
    protected $fillable = [
        'id_user',
        'license',
        'photo',
        'site_id'
    ];

    /**
     * Relación con User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Relación con Site
     */
    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function driverUnits()
    {
        return $this->hasMany(DriverUnit::class, 'id_driver');
    }

    /**
     * Relación con unidades a través de la tabla pivot
     */
    public function units()
    {
        return $this->belongsToMany(Unit::class, 'driver_unit', 'id_driver', 'id_unit')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    // Scope para filtrar por site
    public function scopeBySite($query, $siteId)
    {
        return $query->where('site_id', $siteId);
    }
}
