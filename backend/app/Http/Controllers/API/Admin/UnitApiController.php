<?php

namespace App\Http\Controllers\API\Admin;

use App\Models\Unit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
class UnitApiController extends Controller
{
    public function index()
    {
        // Solo con la relación al sitio
        $units = Unit::with('site')->get();
        return response()->json($units);
    }

  public function store(Request $request)
{
    $validated = $request->validate([
        'plate' => 'required|string|max:255',
        'capacity' => 'required|integer',
        'photo' => 'nullable|image|max:2048', // imagen max 2MB
        'site_id' => 'required|exists:sites,id',
    ]);

    if ($request->hasFile('photo')) {
        $file = $request->file('photo');
        $path = $file->store('units_photos', 'public'); // guarda en storage/app/public/units_photos
        $validated['photo'] = $path; // guarda la ruta relativa
    }

    $unit = Unit::create($validated);
    return response()->json($unit, 201);
}



    public function show($id)
    {
        $unit = Unit::with('site')->findOrFail($id);
        return response()->json($unit);
    }

public function update(Request $request, $id)
{
    $unit = Unit::findOrFail($id);

    $validated = $request->validate([
        'plate' => 'sometimes|string|max:255',
        'capacity' => 'sometimes|integer',
        'photo' => 'nullable|image|max:2048',
        'site_id' => 'sometimes|exists:sites,id',
    ]);

    if ($request->hasFile('photo')) {
        // Elimina la foto antigua si quieres
        if ($unit->photo && \Storage::disk('public')->exists($unit->photo)) {
            \Storage::disk('public')->delete($unit->photo);
        }

        $file = $request->file('photo');
        $path = $file->store('units_photos', 'public');
        $validated['photo'] = $path;
    }

    $unit->update($validated);
    return response()->json($unit);
}


    public function destroy($id)
    {
        $unit = Unit::findOrFail($id);
        $unit->delete();
        return response()->json(['message' => 'Unidad eliminada']);
    }
}
