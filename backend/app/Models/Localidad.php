<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Localidad extends Model
{
    use HasFactory;

    // Definir los campos que pueden ser asignados de forma masiva
    protected $table = 'localities';
    protected $fillable = [
    'longitude',
    'latitude',
    'locality',
    'street',
    'postal_code',
    'municipality',
    'state',
    'country',
    'locality_type',
];

    // Si tienes timestamps habilitados (creado_at y actualizado_at)
    public $timestamps = true;
    public function rutas_salida()
    {
        return $this->hasMany(Ruta::class, 'id_location_s');
    }

    public function rutas_llegada()
    {
        return $this->hasMany(Ruta::class, 'id_location_f');
    }
}
