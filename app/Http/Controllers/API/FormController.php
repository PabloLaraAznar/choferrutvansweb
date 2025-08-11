<?php
namespace App\Http\Controllers\API;

use App\Models\Form;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FormController extends Controller
{
    public function index() {
        return response()->json(Form::all());
    }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required|string',
            // Otros campos relevantes
        ]);
        $form = Form::create($request->only(['title']));
        return response()->json($form, 201);
    }

    public function show($id) {
        return response()->json(Form::findOrFail($id));
    }

    public function update(Request $request, $id) {
        $form = Form::findOrFail($id);
        $request->validate([
            'title' => 'sometimes|required|string',
            // Otros campos
        ]);
        $form->update($request->only(['title']));
        return response()->json($form);
    }

    public function destroy($id) {
        Form::destroy($id);
        return response()->json(['message' => 'Formulario eliminado'], 204);
    }
}