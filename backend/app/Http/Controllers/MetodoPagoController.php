<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MetodoPago;

class MetodoPagoController extends Controller
{
    public function index()
    {
        $metodos = MetodoPago::latest()->get();
        return view('metodoPago.index', compact('metodos'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);

        MetodoPago::create($request->only('name'));

        return redirect()->route('metodoPago.index')->with('success', 'Método de pago registrado.');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required']);

        $metodo = MetodoPago::findOrFail($id);
        $metodo->update($request->only('name'));

        return redirect()->route('metodoPago.index')->with('updated', 'Método de pago actualizado.');
    }

    public function destroy($id)
    {
        $metodo = MetodoPago::findOrFail($id);
        $metodo->delete();

        return redirect()->route('metodoPago.index')->with('deleted', 'Método de pago eliminado.');
    }
}
