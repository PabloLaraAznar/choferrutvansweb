<?php

namespace App\Http\Controllers;

use App\Models\Cashier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CashierController extends Controller
{
     public function index(Request $request)
    {
        $query = Cashier::with(['user', 'site']);
        
        // Filtrar por site_id si se proporciona
        if ($request->has('site_id') && $request->site_id) {
            $query->where('site_id', $request->site_id);
        }
        
        $cashiers = $query->get();
        return view('empleados.cashiers.index', compact('cashiers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'photo'    => 'nullable|image|max:2048',
            'site_id'  => 'required|exists:sites,id',
        ]);

        DB::transaction(function () use ($request) {
            // Crear usuario
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Asignar rol de coordinator
            $user->assignRole('cashier');

            // Generar el código de empleado automáticamente
            $lastCashier = Cashier::latest('id')->first();
            $employeeCode = $lastCashier ? str_pad($lastCashier->id + 1, 4, '0', STR_PAD_LEFT) : '0001';

            // Crear coordinador con ID de usuario recién creado
            $cashier = new Cashier();
            $cashier->id_user = $user->id;
            $cashier->employee_code = $employeeCode;

            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $extension = $file->getClientOriginalExtension();

                // Normalizar el nombre
                $normalizeString = function ($string) {
                    $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
                    return preg_replace('/[^a-zA-Z0-9_]/', '_', strtolower($string));
                };

                // Definir carpeta y nombre de archivo
                $nameSlug = $normalizeString($user->name);
                $folderPath = "cashiers/{$user->id}/{$nameSlug}";
                $filename = "cashier_photo.{$extension}";

                // Crear carpeta si no existe
                Storage::disk('public')->makeDirectory($folderPath);

                // Guardar la foto en la estructura especificada
                $path = $file->storeAs($folderPath, $filename, 'public');

                $cashier->photo = $path;
            }

            $cashier->save();
        });

        return redirect()->route('cashiers.index')->with('success', 'Cajero creado correctamente.');
    }

    public function update(Request $request, Cashier $cashier)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $cashier->user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'photo'    => 'nullable|image|max:2048',
        ]);

        DB::transaction(function () use ($request, $cashier) {
            $user = $cashier->user;

            // Función para normalizar el nombre
            $normalizeString = function ($string) {
                $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
                return preg_replace('/[^a-zA-Z0-9_]/', '_', strtolower($string));
            };

            $oldNameSlug = $normalizeString($user->name);
            $oldFolder = "cashier/{$user->id}/{$oldNameSlug}";
            $oldFolderPath = storage_path("app/public/{$oldFolder}");

            // Actualizar datos del usuario
            $user->name  = $request->name;
            $user->email = $request->email;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            $newNameSlug = $normalizeString($user->name);
            $newFolder = "cashier/{$user->id}/{$newNameSlug}";
            $newFolderPath = storage_path("app/public/{$newFolder}");

            Log::info("Updating cashier ID {$user->id} from {$oldFolder} to {$newFolder}");

            // 🔹 Renombrar carpeta si el nombre cambió
            if ($oldNameSlug !== $newNameSlug) {
                if (file_exists($oldFolderPath) && is_dir($oldFolderPath)) {
                    Log::info("Old folder exists: {$oldFolderPath}");

                    if (!file_exists($newFolderPath)) {
                        rename(realpath($oldFolderPath), $newFolderPath);
                        Log::info("Renamed folder {$oldFolder} to {$newFolder}");
                        $cashier->photo = str_replace($oldFolder, $newFolder, $cashier->photo);
                    } else {
                        Log::error("New folder {$newFolder} already exists.");
                    }
                } else {
                    Log::error("Old folder {$oldFolder} does not exist at {$oldFolderPath}");
                }
            }

            // 🔹 Guardar nueva foto si se subió
            if ($request->hasFile('photo')) {
                Log::info("New photo uploaded.");

                $extension = $request->file('photo')->getClientOriginalExtension();
                $filename = "cashier_photo.{$extension}";

                // ✅ Crear carpeta si no existe
                Storage::disk('public')->makeDirectory($newFolder);

                // ✅ Eliminar la foto previa antes de guardar la nueva
                if ($cashier->photo && Storage::disk('public')->exists($cashier->photo)) {
                    Storage::disk('public')->delete($cashier->photo);
                    Log::info("Deleted previous photo: {$cashier->photo}");
                }

                // ✅ Guardar nueva foto en la carpeta correcta
                $path = $request->file('photo')->storeAs($newFolder, $filename, 'public');
                Log::info("Stored new photo at: {$path}");

                $cashier->photo = $path;
            }

            $cashier->save();

            Log::info("Cashier updated successfully for user ID {$user->id}");
        });

        return $request->ajax()
            ? response()->json(['message' => 'Cajero actualizado correctamente'])
            : redirect()->route('cashiers.index')->with('success', 'Cajero actualizado correctamente.');
    }

    public function destroy($id)
    {
        $cashier = Cashier::findOrFail($id);
        $user = $cashier->user;

        DB::transaction(function () use ($cashier, $user) {
            // 🔹 Obtener la carpeta específica coordinators/{user_id}/{name_slug}
            if ($cashier->photo) {
                $nameSlug = preg_replace('/[^a-zA-Z0-9_]/', '_', strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $user->name)));
                $folderPath = "cashiers/{$user->id}/{$nameSlug}";

                // 🔹 Eliminar solo la carpeta del nombre
                if (Storage::disk('public')->exists($folderPath)) {
                    Storage::disk('public')->deleteDirectory($folderPath);
                    Log::info("Deleted user-specific folder: {$folderPath}");
                } else {
                    Log::warning("Folder not found: {$folderPath}");
                }
            }

            // 🔹 Verificar si coordinators/{user_id} está vacío y eliminarla
            $userFolder = "cashiers/{$user->id}";
            if (Storage::disk('public')->exists($userFolder) && count(Storage::disk('public')->allFiles($userFolder)) === 0) {
                Storage::disk('public')->deleteDirectory($userFolder);
                Log::info("Deleted empty user ID folder: {$userFolder}");
            }

            // 🔹 Eliminar registros de coordinador y usuario
            $cashier->delete();
            $user->delete();
        });

        return redirect()->route('cashiers.index')->with('success', 'Cajero eliminado correctamente.');
    }
}
