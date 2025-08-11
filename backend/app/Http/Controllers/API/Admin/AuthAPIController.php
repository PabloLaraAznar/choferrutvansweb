<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthAPIController extends Controller
{
    public function login_admin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        $user = Auth::user();
        $user->load('roles'); // <-- Cargar roles

        $token = $user->createToken('flutter-token')->plainTextToken;

        // Construir URL completa para la foto de perfil
        $profilePhotoUrl = $user->profile_photo_path
            ? url('storage/' . $user->profile_photo_path)
            : null;  // <-- No envía URL por defecto

        // Convertir usuario a array para agregar/modificar campos
        $userArray = $user->toArray();
        $userArray['profile_photo_url'] = $profilePhotoUrl;

        // Agregar los roles como array de objetos { id, name }
        $userArray['roles'] = $user->roles->map(function ($role) {
            return [
                'id' => $role->id,
                'name' => $role->name,
            ];
        })->toArray();

        return response()->json([
            'token' => $token,
            'user' => $userArray,
        ]);
    }

    public function user(Request $request)
    {
        $user = $request->user();
        $user->load('roles'); // <-- Cargar roles

        $profilePhotoUrl = $user->profile_photo_path
            ? url('storage/' . $user->profile_photo_path)
            : null; // <-- No envía URL por defecto

        $userArray = $user->toArray();
        $userArray['profile_photo_url'] = $profilePhotoUrl;

        // Agregar roles también aquí
        $userArray['roles'] = $user->roles->map(function ($role) {
            return [
                'id' => $role->id,
                'name' => $role->name,
            ];
        })->toArray();

        return response()->json($userArray);
    }
       public function logout(Request $request)
{
    // Elimina solo el token actual (el que se usó en la petición)
    $request->user()->currentAccessToken()->delete();

    return response()->json(['message' => 'Sesión cerrada correctamente']);
}
}
