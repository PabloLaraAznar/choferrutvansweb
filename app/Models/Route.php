<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Route extends Model
{
    use HasFactory;

    protected $table = 'routes';

    protected $fillable = [
        'id_location_s',
        'id_location_f',
        'site_id'
    ];

    // Relación con Site
    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function locationStart()
    {
        return $this->belongsTo(Locality::class, 'id_location_s');
    }

    public function locationEnd()
    {
        return $this->belongsTo(Locality::class, 'id_location_f');
    }

    // Relación con units
    public function units()
    {
        return $this->belongsToMany(Unit::class, 'route_unit', 'id_route', 'id_unit')
                    ->withTimestamps();
    }

    // Relación con sales
    public function sales()
    {
        return $this->hasMany(Sale::class, 'id_route');
    }

    // Scope para filtrar por site
    public function scopeBySite($query, $siteId)
    {
        return $query->where('site_id', $siteId);
    }
}
