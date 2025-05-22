<?php  
namespace App\Http\Controllers;  

use Illuminate\Http\Request;
use App\Models\Localidad;
use Illuminate\Support\Facades\Log;  

class LocalidadesController extends Controller 
{
    public function index()     
    {
        // Cargar todas las localidades incluyendo el ID
        $localidades = Localidad::select('id', 'longitude', 'latitude', 'locality', 'street', 'postal_code')->get(); 

        $localidadesCount = Localidad::count();

        return view('localidades.index', compact('localidades', 'localidadesCount'));
    
    } 

    public function store(Request $request)     
    {
        // Validar y guardar la nueva localidad
        $validated = $request->validate([
            'longitude' => 'required',
            'latitude' => 'required',
            'locality' => 'required|string|max:255',
            'street' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:10',
        ]);

        Localidad::create($validated);

        // Redirigir con mensaje de éxito
        return redirect()->route('localidades.index')->with('success', '¡Ubicación guardada correctamente!');
    } 

    public function update(Request $request, $id)     
    {
        try {
            $localidad = Localidad::findOrFail($id);

            $validated = $request->validate([
                'longitude' => 'required|numeric',
                'latitude' => 'required|numeric',
                'locality' => 'required|string|max:255',
                'street' => 'nullable|string|max:255',
                'postal_code' => 'nullable|string|max:10',
            ]);

            $localidad->update($validated);

            return redirect()->route('localidades.index')->with('success', '¡Ubicación actualizada con éxito!');
        } catch (\Exception $e) {
            return redirect()->route('localidades.index')->with('error', 'Error al actualizar la ubicación.');
        }
    }

    public function destroy($id)
    {
        try {
            $localidad = Localidad::findOrFail($id);
            $localidad->delete();
            return redirect()->route('localidades.index')->with('success', '¡Ubicación eliminada correctamente!');
        } catch (\Exception $e) {
            return redirect()->route('localidades.index')->with('error', 'Error al eliminar la ubicación.');
        }
    }
}
