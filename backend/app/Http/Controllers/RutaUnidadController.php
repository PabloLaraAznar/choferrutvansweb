<?php

namespace App\Http\Controllers;

use App\Models\RutaUnidad;
use Illuminate\Http\Request;


class RutaUnidadController extends Controller
{
    /**
     * Mostrar listado de registros.
     */
    public function index()
{
    $datos = RutaUnidad::with(['ruta', 'conductorUnidad', 'ubicacionIntermedia'])->get();
    return view('ruta_unidad.index', compact('datos'));
}

    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        return view('ruta_unidad.create');
    }

    /**
     * Guardar nuevo registro.
     */
    public function store(Request $request)
    {
        // Validación (opcional, recomendable)
        $request->validate([
            'id_route' => 'required|integer',
            'id_driver_unit' => 'required|integer',
            'intermediate_location_id' => 'nullable|integer',
            'price' => 'required|numeric',
        ]);

        RutaUnidad::create($request->all());

        return redirect()->route('ruta_unidad.index')->with('success', 'Registro creado correctamente.');
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit($id)
    {
        $rutaUnidad = RutaUnidad::findOrFail($id);
        return view('ruta_unidad.edit', compact('rutaUnidad'));
    }

    /**
     * Actualizar un registro.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_route' => 'required|integer',
            'id_driver_unit' => 'required|integer',
            'intermediate_location_id' => 'nullable|integer',
            'price' => 'required|numeric',
        ]);

        $rutaUnidad = RutaUnidad::findOrFail($id);
        $rutaUnidad->update($request->all());

        return redirect()->route('ruta_unidad.index')->with('success', 'Registro actualizado correctamente.');
    }

    /**
     * Eliminar un registro.
     */
    public function destroy($id)
    {
        RutaUnidad::destroy($id);
        return redirect()->route('ruta_unidad.index')->with('success', 'Registro eliminado correctamente.');
    }
}
