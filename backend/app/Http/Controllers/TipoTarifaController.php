<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoTarifa;
use Illuminate\Support\Facades\Log;

class TipoTarifaController extends Controller
{
    /**
     * Muestra la lista de tipos de tarifa.
     */
    public function index()
    {
        $tarifas = TipoTarifa::all();
        return view('tarifas.index', compact('tarifas'));
    }

    /**
     * Muestra el formulario para crear una nueva tarifa.
     */
    public function create()
    {
        return view('tarifas.create');
    }

    /**
     * Guarda una nueva tarifa en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'porcentaje' => 'required|numeric',
            'descripcion' =>  'nullable|string',
        ]);

        TipoTarifa::create([
            'name' => $request->input('nombre'),
            'percentage' => $request->input('porcentaje'),
            'description' => $request->input('descripcion'),
        ]);

        return redirect()->route('tarifas.index')->with('success', '¡Tipo de tarifa creado exitosamente!');
    }

    /**
     * Muestra el formulario para editar una tarifa existente.
     */
    public function edit(TipoTarifa $tipoTarifa)
    {
        return view('tarifas.edit', compact('tipoTarifa'));
    }

    /**
     * Actualiza una tarifa en la base de datos.
     */
    public function update(Request $request, TipoTarifa $tipoTarifa)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'porcentaje' => 'required|numeric|min:0|max:100',
            'descripcion' => 'nullable|string|max:500'
        ]);

        $tipoTarifa->update([
            'name' => $request->input('nombre'),
            'percentage' => $request->input('porcentaje'),
            'description' => $request->input('descripcion'),
        ]);

        return redirect()->route('tarifas.index')->with('success', '¡Tipo de tarifa actualizada correctamente!');
    }

    /**
     * Elimina una tarifa de la base de datos.
     */
    public function destroy(TipoTarifa $tipoTarifa)
    {
        try {
            $tipoTarifa->delete();
            return response()->json(['success' => true, 'message' => '¡Tipo de tarifa eliminado correctamente!']);
        } catch (\Exception $e) {
            Log::error('Error al eliminar tipo de tarifa: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al eliminar el tipo de tarifa'], 500);
        }
    }

    /**
     * Devuelve todos los tipos de tarifa en formato JSON (API).
     */
    public function apiIndex()
    {
        return response()->json(TipoTarifa::all());
    }
}
