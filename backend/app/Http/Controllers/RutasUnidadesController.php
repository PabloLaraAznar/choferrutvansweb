<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RutaUnidad;
use App\Models\Ruta;
use App\Models\DriverUnit;
use App\Models\Localidad;

class RutasUnidadesController extends Controller
{
    public function index()
    {
        $rutaUnidades = RutaUnidad::with([
            'ruta.source',
            'ruta.destination',
            'driverUnit.unit',
            'driverUnit.driver.user'
        ])->get();

        $rutas = Ruta::with(['source', 'destination'])->get();
        $driverUnits = DriverUnit::with(['unit', 'driver.user'])->get();
        $localidades = Localidad::all(); 

        return view('rutaunidad.index', compact('rutaUnidades', 'rutas', 'driverUnits', 'localidades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_route' => 'required|exists:routes,id',
            'id_driver_unit' => 'required|exists:driver_unit,id',
            'price' => 'required|numeric|min:0'
        ]);

        RutaUnidad::create($request->only(['id_route', 'id_driver_unit', 'price']));

        return redirect()->route('rutas-unidades.index')->with('success', 'Ruta asignada correctamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_route' => 'required|exists:routes,id',
            'id_driver_unit' => 'required|exists:driver_unit,id',
            'price' => 'required|numeric|min:0'
        ]);

        $rutaUnidad = RutaUnidad::findOrFail($id);
        $rutaUnidad->update($request->only(['id_route', 'id_driver_unit', 'price']));

        return redirect()->route('rutas-unidades.index')->with('success', 'Asignación actualizada correctamente.');
    }

    public function destroy($id)
    {
        $registro = RutaUnidad::findOrFail($id);
        $registro->delete();

        return redirect()->route('rutas-unidades.index')->with('success', 'Asignación eliminada.');
    }
}
