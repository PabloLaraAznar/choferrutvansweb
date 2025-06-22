<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Horario;
use Illuminate\Support\Facades\Log;

class HorarioController extends Controller
{
    public function index()
    {
        $horarios = Horario::all();
        return view('horarios.index', compact('horarios'));
    }

    public function apiIndex()
    {
    return response()->json(Horario::all());
    }

    public function create()
    {
        return view('hora.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'horaLlegada' => 'required|date_format:H:i',
            'horaSalida' => 'required|date_format:H:i',
            'dia' => 'required|string|max:50',
        ]);

        Horario::create($request->only(['horaLlegada', 'horaSalida', 'dia']));
        return redirect()->route('horarios.index')->with('success', '¡Horario creado exitosamente!');
    }

    public function update(Request $request, Horario $horario)
    {
        $request->validate([
            'horaLlegada' => 'required|date_format:H:i',
            'horaSalida' => 'required|date_format:H:i',
            'dia' => 'required|string|max:50',
        ]);

        $horario->update($request->only(['horaLlegada', 'horaSalida', 'dia']));
        return redirect()->route('horarios.index')->with('success', '¡Horario actualizado correctamente!');
    }

    public function destroy(Horario $horario)
    {
        try {
            $horario->delete();
            return response()->json(['success' => true, 'message' => '¡Horario eliminado correctamente!']);
        } catch (\Exception $e) {
            Log::error('Error al eliminar horario: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al eliminar el horario'], 500);
        }
    }
}
