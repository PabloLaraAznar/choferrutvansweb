<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Route;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    // Listar todas las rutas con localidades de inicio y fin
    public function index()
    {
        $routes = Route::with(['locationStart', 'locationEnd'])->get();
        return response()->json($routes);
    }

    // Crear nueva ruta
    public function store(Request $request)
    {
        $data = $request->validate([
            'location_s_id' => 'required|exists:localities,id',
            'location_f_id' => 'required|exists:localities,id|different:location_s_id',
            'site_id' => 'nullable|exists:sites,id',
        ]);

        $route = Route::create($data);

        return response()->json(
            Route::with(['locationStart', 'locationEnd'])->find($route->id),
            201
        );
    }

    // Mostrar una ruta específica
    public function show($id)
    {
        $route = Route::with(['locationStart', 'locationEnd'])->find($id);

        if (!$route) {
            return response()->json(['message' => 'Ruta no encontrada'], 404);
        }

        return response()->json($route);
    }

    // Actualizar una ruta
    public function update(Request $request, $id)
    {
        $route = Route::find($id);

        if (!$route) {
            return response()->json(['message' => 'Ruta no encontrada'], 404);
        }

        $data = $request->validate([
            'location_s_id' => 'sometimes|required|exists:localities,id',
            'location_f_id' => 'sometimes|required|exists:localities,id|different:location_s_id',
            'site_id' => 'nullable|exists:sites,id',
        ]);

        $route->update($data);

        return response()->json(
            Route::with(['locationStart', 'locationEnd'])->find($route->id)
        );
    }

    // Eliminar una ruta
    public function destroy($id)
    {
        $route = Route::find($id);

        if (!$route) {
            return response()->json(['message' => 'Ruta no encontrada'], 404);
        }

        $route->delete();

        return response()->json(['message' => 'Ruta eliminada correctamente']);
    }
}
