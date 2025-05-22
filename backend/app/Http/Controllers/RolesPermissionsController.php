<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesPermissionsController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        return view('roles-permissions.roles-permissions.index', compact('roles', 'permissions'));
    }

    public function update(Request $request, $roleId)
    {
        // Obtener el rol correctamente
        $role = Role::findOrFail($roleId);

        Log::info("Actualizando permisos para el rol ID: " . $role->id);

        // Verificar si se enviaron permisos
        if (!$request->has('permissions') || empty($request->permissions)) {
            Log::warning("No se recibieron permisos en la solicitud.");
            return redirect()->route('roles-permissions.index')->with('error', 'No se recibieron permisos.');
        }

        $permissions = Permission::whereIn('id', $request->permissions)->get();
        Log::info("Permisos validados: ", $permissions->pluck('name')->toArray());

        // Limpiar los permisos actuales y asignar los nuevos
        $role->syncPermissions($permissions);

        Log::info("Permisos asignados correctamente al rol ID: " . $role->id);

        return redirect()->route('roles-permissions.index')->with('success', '¡Permisos actualizados correctamente!');
    }
    public function edit(Role $role)
    {
        $assignedPermissions = $role->permissions->pluck('id')->toArray();

        return response()->json([
            'assigned_permissions' => $assignedPermissions,
            'permissions' => Permission::select('id', 'name')->get()
        ]);
    }
}
