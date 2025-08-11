<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    // Listar todos los usuarios
    public function index()
    {
        return response()->json(User::all());
    }

    // Crear nuevo usuario
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'address' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'address' => $request->address,
            'phone_number' => $request->phone_number,
        ]);

        return response()->json($user, 201);
    }

    // Método para login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    // Obtener datos del usuario autenticado
    public function getUser(Request $request)
    {
        $userId = $request->input('user_id');
        if (!$userId) {
            return response()->json(['error' => 'Falta el user_id'], 400);
        }

        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone_number' => $user->phone_number,
            'address' => $user->address,
            'profile_photo_path' => $user->profile_photo_path,
        ], 200);
    }

    // Actualizar datos del usuario
    public function updateUser(Request $request)
    {
        $userId = $request->input('user_id');
        if (!$userId) {
            return response()->json(['error' => 'Falta el user_id'], 400);
        }

        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        $allData = $request->all();
        \Log::info('Datos recibidos: ', $allData); // Depuración de todos los datos recibidos

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'phone_number' => 'sometimes|nullable|string|max:255',
            'address' => 'sometimes|nullable|string|max:255',
        ]);

        \Log::info('Datos validados: ', $validated); // Depuración de datos validados

        $user->update($validated);

        return response()->json(['message' => 'Datos actualizados con éxito'], 200);
    }

    // Subir foto de perfil
    public function uploadPhoto(Request $request)
    {
        $userId = $request->input('user_id');
        if (!$userId) {
            return response()->json(['error' => 'Falta el user_id'], 400);
        }

        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = 'profile_' . time() . '.' . $file->getClientOriginalExtension();
            // Cambiado disco a 'public' para que la imagen quede en storage/app/public
            $path = $file->storeAs("users/{$user->id}", $fileName, 'public');

            $user->profile_photo_path = Storage::url($path);
            $user->save();

            return response()->json([
                'profile_photo_path' => asset($user->profile_photo_path)
            ], 200);
        }

        return response()->json(['error' => 'No se proporcionó una foto'], 400);
    }
}
