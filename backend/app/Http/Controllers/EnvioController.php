<?php
namespace App\Http\Controllers\Api;
namespace App\Http\Controllers;

use App\Models\Envio;
use App\Models\Ruta;
use App\Models\Horario;
use App\Models\RutaUnidad;
use Illuminate\Http\Request;

class EnvioController extends Controller
{
    public function index()
    {
        $envios = Envio::with(['route', 'schedules', 'route_unit'])->get();
        $rutas = Ruta::all();
        $horarios = Horario::all();
        $rutasUnidades = RutaUnidad::all();
        return view('envios.index', compact('envios', 'rutas', 'horarios', 'rutasUnidades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sender_name' => 'required|string|max:255',
            'receiver_name' => 'required|string|max:255',
            'total' => 'required|numeric',
            'route_id' => 'required',
            'schedule_id' => 'required',
            'route_unit_id' => 'required',
            'photo' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('envios', 'public');
        }

        Envio::create($data);
        return redirect()->route('envios.index')->with('success', 'Envío creado');
    }

    public function update(Request $request, Envio $envio)
    {
        $request->validate([
            'sender_name' => 'required|string|max:255',
            'receiver_name' => 'required|string|max:255',
            'total' => 'required|numeric',
            'route_id' => 'required',
            'schedule_id' => 'required',
            'route_unit_id' => 'required',
            'photo' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('envios', 'public');
        }

        $envio->update($data);
        return redirect()->route('envios.index')->with('success', 'Envío actualizado');
    }

    public function destroy(Envio $envio)
    {
        $envio->delete();
        return redirect()->route('envios.index')->with('success', 'Envío eliminado');
    }
    public function apiIndex()
{
    $envios = Envio::all()->map(function ($envio) {
        return [
            'id' => $envio->id,
            'sender_name' => $envio->sender_name,
            'receiver_name' => $envio->receiver_name,
            'total' => $envio->total,
            'description' => $envio->description,
            'photo_url' => $envio->photo ? asset('storage/' . $envio->photo) : null,
            'route_unit_id' => $envio->route_unit_id,
            'schedule_id' => $envio->schedule_id,
            'route_id' => $envio->route_id,
        ];
    });

    return response()->json($envios);
}

   
}


