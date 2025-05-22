<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Facades\Log;

class RolesController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('roles-permissions.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('roles-permissions.roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name|min:3|max:50',
        ]);

        Role::create(['name' => $request->name]);
        return redirect()->route('roles.index')->with('success', '¡Rol creado exitosamente!');
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|min:3|max:50|unique:roles,name,' . $role->id,
        ]);

        $role->update(['name' => $request->name]);
        return redirect()->route('roles.index')->with('success', '¡Rol actualizado correctamente!');
    }

    public function destroy(Role $role)
    {
        try {
            if ($role->users()->count() > 0) {
                return response()->json(['success' => false, 'message' => 'No se puede eliminar: el rol está asignado a usuarios.']);
            }

            $role->delete();
            return response()->json(['success' => true, 'message' => '¡Rol eliminado correctamente!']);
        } catch (\Exception $e) {
            Log::error('Error al eliminar rol: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al eliminar el rol'], 500);
        }
    }
}
