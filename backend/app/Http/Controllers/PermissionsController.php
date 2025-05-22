<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{
    public function index()
    {
        $permissions = Permission::all(); // Obtener todos los permisos
        return view('roles-permissions.permissions.index', compact('permissions'));
    }

    public function store(Request $request)
    {
        Permission::create(['name' => $request->name]);
        return redirect()->route('permissions.index')->with('success', '¡Permiso creado exitosamente!');
    }

    public function update(Request $request, Permission $permission)
    {
        $permission->update(['name' => $request->name]);
        return redirect()->route('permissions.index')->with('success', '¡Permiso actualizado correctamente!');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return response()->json(['success' => true, 'message' => '¡Permiso eliminado correctamente!']);
    }
}
