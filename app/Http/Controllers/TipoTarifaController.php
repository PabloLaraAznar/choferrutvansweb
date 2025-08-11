<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Rate;
use Illuminate\Support\Facades\Log;

class TipoTarifaController extends Controller
{
    /**
     * Muestra la lista de servicios y tarifas.
     */
    public function index()
    {
        $servicios = Service::with('rates')->get();
        return view('tarifas.index', compact('servicios'));
    }

    /**
     * Muestra el formulario para crear un nuevo servicio con tarifa.
     */
    public function create()
    {
        return view('tarifas.create');
    }

    /**
     * Guarda un nuevo servicio con tarifa en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric',
            'descripcion' => 'nullable|string',
            'site_id' => 'required|exists:sites,id'
        ]);

        // Crear el servicio
        $service = Service::create([
            'name' => $request->input('nombre'),
            'description' => $request->input('descripcion'),
            'price' => $request->input('precio'),
            'site_id' => $request->input('site_id'),
            'status' => 'active'
        ]);

        // Crear la tarifa asociada
        Rate::create([
            'id_service' => $service->id,
            'rate_type' => 'standard',
            'rate_cost' => $request->input('precio'),
            'site_id' => $request->input('site_id'),
            'status' => 'active'
        ]);

        return redirect()->route('tarifas.index')->with('success', '¡Servicio y tarifa creados exitosamente!');
    }

    /**
     * Muestra el formulario para editar un servicio existente.
     */
    public function edit(Service $service)
    {
        return view('tarifas.edit', compact('service'));
    }

    /**
     * Actualiza un servicio en la base de datos.
     */
    public function update(Request $request, Service $service)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'descripcion' => 'nullable|string|max:500'
        ]);

        $service->update([
            'name' => $request->input('nombre'),
            'price' => $request->input('precio'),
            'description' => $request->input('descripcion'),
        ]);

        // Actualizar la tarifa asociada
        $service->rates()->update([
            'rate_cost' => $request->input('precio')
        ]);

        return redirect()->route('tarifas.index')->with('success', '¡Servicio actualizado correctamente!');
    }

    /**
     * Elimina un servicio de la base de datos.
     */
    public function destroy(Service $service)
    {
        try {
            $service->delete();
            return response()->json(['success' => true, 'message' => '¡Servicio eliminado correctamente!']);
        } catch (\Exception $e) {
            Log::error('Error al eliminar servicio: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al eliminar el servicio'], 500);
        }
    }

    /**
     * Devuelve todos los servicios en formato JSON (API).
     */
    public function apiIndex()
    {
        return response()->json(Service::with('rates')->get());
    }
}
