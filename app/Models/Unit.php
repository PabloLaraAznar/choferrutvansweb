<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'units';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'model',
        'plate',
        'capacity',
        'photo'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'capacity' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the reservations for the unit.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'unit_id');
    }

    /**
     * Get the photo URL attribute.
     * (Ejemplo de accesor para manejar la URL de la foto)
     */
    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }
        return null;
    }

    /**
     * Scope para unidades disponibles (ejemplo)
     */
    public function scopeAvailable($query)
    {
        return $query->whereDoesntHave('reservations', function($q) {
            $q->where('status', 'active')
              ->whereDate('travel_date', now()->toDateString());
        });
    }

    /**
     * Obtener asientos ocupados para una fecha específica
     */
    public function occupiedSeats($date = null)
    {
        $date = $date ?: now()->toDateString();

        return $this->reservations()
            ->whereDate('travel_date', $date)
            ->where('status', '!=', 'cancelled')
            ->pluck('seats')
            ->flatten()
            ->unique()
            ->values()
            ->toArray();
    }

    /**
     * Obtener asientos disponibles para una fecha
     */
    public function availableSeats($date = null)
    {
        $occupied = $this->occupiedSeats($date);
        $allSeats = range(1, $this->capacity);

        return array_values(array_diff($allSeats, $occupied));
    }

    /**
     * Verificar si un asiento está disponible
     */
    public function isSeatAvailable($seatNumber, $date = null)
    {
        return in_array($seatNumber, $this->availableSeats($date));
    }
}
