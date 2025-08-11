<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

use App\Models\User;
use App\Models\Driver;

class DriverApiController extends Controller
{
    /**
     * Lista de conductores
     */
    
public function index()
{
    // Obtener el ID del rol 'driver'
    $driverRoleId = Role::where('name', 'driver')->value('id');

    $conductores = DB::table('drivers as d')
        ->join('users as u', 'd.user_id', '=', 'u.id')
        ->join('model_has_roles as r', 'u.id', '=', 'r.model_id')
        ->where('r.role_id', $driverRoleId)
        ->where('r.model_type', User::class)
        ->select(
            'd.id AS driver_id',
            'u.id AS user_id',
            'u.name AS nombre',
            'u.phone_number AS telefono',
            'u.email AS email_usuario',
            'd.license AS licencia',
            'd.photo AS foto_conductor_path',
            'u.profile_photo_path AS foto_perfil_usuario_path'
        )
        ->get();

    $conductores = $conductores->map(function ($conductor) {
        $fotoUrl = null;

        if ($conductor->foto_conductor_path) {
            $fotoUrl = filter_var($conductor->foto_conductor_path, FILTER_VALIDATE_URL)
                ? $conductor->foto_conductor_path
                : URL::to('storage/' . $conductor->foto_conductor_path);
        } elseif ($conductor->foto_perfil_usuario_path) {
            $fotoUrl = filter_var($conductor->foto_perfil_usuario_path, FILTER_VALIDATE_URL)
                ? $conductor->foto_perfil_usuario_path
                : URL::to('storage/' . $conductor->foto_perfil_usuario_path);
        }

        $conductor->foto = $fotoUrl;

        unset($conductor->foto_conductor_path, $conductor->foto_perfil_usuario_path);

        return $conductor;
    });

    return response()->json([
        'total' => $conductores->count(),
        'data' => $conductores
    ]);
}

    /**
     * Crear nuevo conductor con foto subida como archivo y asignar rol
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'licencia' => 'required|string|max:255|unique:drivers,license',
            'telefono' => 'nullable|string|max:20',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'foto_conductor' => 'nullable|image|max:2048',
        ]);

        // Guardar foto
        $photoPath = null;
        if ($request->hasFile('foto_conductor')) {
            $photoPath = $request->file('foto_conductor')->store('drivers', 'public');
        }

        // Crear usuario
        $user = User::create([
            'name' => $validatedData['nombre'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'phone_number' => $validatedData['telefono'] ?? null,
        ]);

        // 👉 Asignar rol 'driver'
        $user->assignRole('driver');

        // Crear conductor asociado
        $driver = Driver::create([
            'user_id' => $user->id,
            'license' => $validatedData['licencia'],
            'photo' => $photoPath,
        ]);

        return response()->json([
            'message' => 'Conductor creado exitosamente',
            'driver' => $driver,
            'user' => $user,
        ], 201);
    }

    /**
     * Mostrar conductor
     */
    public function show(Driver $driver)
    {
        $driver->load('user');

        $fotoUrl = null;
        if ($driver->photo) {
            $fotoUrl = filter_var($driver->photo, FILTER_VALIDATE_URL)
                ? $driver->photo
                : URL::to('storage/' . $driver->photo);
        } elseif ($driver->user && $driver->user->profile_photo_path) {
            $fotoUrl = filter_var($driver->user->profile_photo_path, FILTER_VALIDATE_URL)
                ? $driver->user->profile_photo_path
                : URL::to('storage/' . $driver->user->profile_photo_path);
        }

        return response()->json([
            'driver_id' => $driver->id,
            'nombre' => $driver->user->name ?? null,
            'telefono' => $driver->user->phone_number ?? null,
            'email_usuario' => $driver->user->email ?? null,
            'licencia' => $driver->license,
            'foto' => $fotoUrl
        ]);
    }

    /**
     * Actualizar conductor
     */
    public function update(Request $request, Driver $driver)
    {
        $user = $driver->user;

        $validatedData = $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'licencia' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('drivers', 'license')->ignore($driver->id)
            ],
            'telefono' => 'nullable|string|max:20',
            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id ?? null)
            ],
            'foto_conductor' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto_conductor')) {
            if ($driver->photo) {
                Storage::disk('public')->delete($driver->photo);
            }
            $photoPath = $request->file('foto_conductor')->store('drivers', 'public');
            $validatedData['foto_conductor'] = $photoPath;
        }

        $driver->update([
            'license' => $validatedData['licencia'] ?? $driver->license,
            'photo' => $validatedData['foto_conductor'] ?? $driver->photo,
        ]);

        if ($user) {
            $dataUser = [];
            if (isset($validatedData['nombre'])) {
                $dataUser['name'] = $validatedData['nombre'];
            }
            if (isset($validatedData['telefono'])) {
                $dataUser['phone_number'] = $validatedData['telefono'];
            }
            if (isset($validatedData['email'])) {
                $dataUser['email'] = $validatedData['email'];
            }
            if (!empty($dataUser)) {
                $user->update($dataUser);
            }
        }

        $driver->load('user');

        $fotoUrl = null;
        if ($driver->photo) {
            $fotoUrl = filter_var($driver->photo, FILTER_VALIDATE_URL)
                ? $driver->photo
                : URL::to('storage/' . $driver->photo);
        } elseif ($driver->user && $driver->user->profile_photo_path) {
            $fotoUrl = filter_var($driver->user->profile_photo_path, FILTER_VALIDATE_URL)
                ? $driver->user->profile_photo_path
                : URL::to('storage/' . $driver->user->profile_photo_path);
        }

        return response()->json([
            'driver_id' => $driver->id,
            'nombre' => $driver->user->name ?? null,
            'telefono' => $driver->user->phone_number ?? null,
            'email_usuario' => $driver->user->email ?? null,
            'licencia' => $driver->license,
            'foto' => $fotoUrl
        ]);
    }

    /**
     * Eliminar conductor
     */
    public function destroy(Driver $driver)
    {
        if ($driver->photo) {
            Storage::disk('public')->delete($driver->photo);
        }

        $driver->delete();

        return response()->json(null, 204);
    }
}
