<?php
namespace App\Http\Controllers\Api;
namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\Route;
use App\Models\RouteUnitSchedule;
use App\Models\RouteUnit;
use Illuminate\Http\Request;

class EnvioController extends Controller
{
    public function index()
    {
        $envios = Shipment::with(['site'])->get();
        $rutas = Route::all();
        $horarios = RouteUnitSchedule::all();
        $rutasUnidades = RouteUnit::all();
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

        Shipment::create($data);
        return redirect()->route('envios.index')->with('success', 'Envío creado');
    }

    public function update(Request $request, Shipment $envio)
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

    public function destroy(Shipment $envio)
    {
        $envio->delete();
        return redirect()->route('envios.index')->with('success', 'Envío eliminado');
    }
    public function apiIndex()
{
    $envios = Shipment::all()->map(function ($envio) {
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


