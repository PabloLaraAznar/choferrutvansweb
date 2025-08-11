<?php

namespace App\Http\Controllers\API\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return response()->json($request->user());
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $user->update($request->only(['name', 'email', 'phone_number', 'address']));

        return response()->json(['message' => 'Perfil actualizado correctamente']);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed|min:6', // puse mínimo 6 para que coincida con Flutter
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'La contraseña actual es incorrecta'], 422);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['message' => 'Contraseña actualizada correctamente']);
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'profile_photo_path' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = $request->user();

        if ($request->hasFile('profile_photo_path')) {
            // Eliminar la foto anterior si existe
            if ($user->profile_photo_path) {
                $oldPath = str_replace('/storage', '', $user->profile_photo_path);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            // Guardar la nueva foto
            $path = $request->file('profile_photo_path')->store('profile-photos', 'public');
            $user->profile_photo_path = '/storage/' . $path;
            $user->save();

            return response()->json([
                'message' => 'Foto de perfil actualizada correctamente',
                'profile_photo_path' => $user->profile_photo_path
            ]);
        }

        return response()->json(['message' => 'No se recibió ninguna imagen'], 422);
    }
}
