<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Localidad;

class LocalidadesController extends Controller
{
    /**
     * Mostrar la lista de localidades y pasar conteo al index.
     */
    public function index()
    {
        $localidades = Localidad::all();
        $localidadesCount = $localidades->count();

        return view('localidades.index', compact('localidades', 'localidadesCount'));
    }

    /**
     * Guardar una nueva localidad en la base de datos.
     */
    public function store(Request $request)
    {
        // Validamos todos los campos: municipality, state y locality_type ahora son nullable
        $validated = $request->validate([
            'longitude'      => 'required|numeric',
            'latitude'       => 'required|numeric',
            'locality'       => 'required|string|max:255',
            'street'         => 'nullable|string|max:255',
            'postal_code'    => 'nullable|string|max:20',
            'municipality'   => 'nullable|string|max:100',
            'state'          => 'nullable|string|max:100',
            'country'        => 'required|string|max:100',
            'locality_type'  => 'nullable|string|max:50',
        ]);

        Localidad::create($validated);

        return redirect()->route('localidades.index')
                         ->with('success', '¡Ubicación guardada correctamente!');
    }

    /**
     * Actualizar una localidad existente.
     */
    public function update(Request $request, $id)
    {
        $localidad = Localidad::findOrFail($id);

        $validated = $request->validate([
            'longitude'      => 'required|numeric',
            'latitude'       => 'required|numeric',
            'locality'       => 'required|string|max:255',
            'street'         => 'nullable|string|max:255',
            'postal_code'    => 'nullable|string|max:20',
            'municipality'   => 'nullable|string|max:100',
            'state'          => 'nullable|string|max:100',
            'country'        => 'required|string|max:100',
            'locality_type'  => 'nullable|string|max:50',
        ]);

        $localidad->update($validated);

        return redirect()->route('localidades.index')
                         ->with('success', '¡Ubicación actualizada con éxito!');
    }

    /**
     * Eliminar una localidad.
     */
    public function destroy($id)
    {
        $localidad = Localidad::findOrFail($id);
        $localidad->delete();

        return redirect()->route('localidades.index')
                         ->with('success', '¡Ubicación eliminada correctamente!');
    }
}
