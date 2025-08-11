<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DriverController extends Controller
{
    public function index()
    {
        // Get current user's sites
        $userSites = Auth::user()->sites->pluck('id');
        
        // Filter drivers by sites the user has access to
        $drivers = Driver::with('user')
            ->whereIn('site_id', $userSites)
            ->get();
            
        return view('empleados.drivers.index', compact('drivers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'license'  => 'required|string|max:100',
            'photo'    => 'nullable|image|max:2048',
        ]);

        DB::transaction(function () use ($request) {
            // Crear usuario
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Asignar rol de driver
            $user->assignRole('driver');

            // Crear chófer con ID de usuario recién creado
            $driver = new Driver();
            $driver->id_user = $user->id;
            $driver->license = $request->license;
            
            // Assign to current user's first site (or allow selection in future)
            $userSites = Auth::user()->sites;
            if ($userSites->count() > 0) {
                $driver->site_id = $userSites->first()->id;
            }

            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $extension = $file->getClientOriginalExtension();

                // Función para eliminar tildes y caracteres especiales
                $normalizeString = function ($string) {
                    $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
                    $string = preg_replace('/[^a-zA-Z0-9_]/', '_', $string); // Reemplazar caracteres especiales con "_"
                    return strtolower($string);
                };

                // Definir carpeta y nombre de archivo según el nuevo formato
                $nameSlug = $normalizeString($user->name);
                $folderPath = "drivers/{$user->id}/{$nameSlug}";
                $filename = "driver_photo.{$extension}";

                // Crear carpeta si no existe
                Storage::disk('public')->makeDirectory($folderPath);

                // Guardar foto en la estructura especificada
                $path = $file->storeAs($folderPath, $filename, 'public');

                $driver->photo = $path;
            }

            $driver->save();
        });

        return redirect()->route('drivers.index')->with('success', 'Chófer creado correctamente.');
    }

    public function update(Request $request, Driver $driver)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $driver->user->id,
            'license'  => 'required|string|max:100',
            'password' => 'nullable|string|min:6|confirmed',
            'photo'    => 'nullable|image|max:2048',
        ]);

        DB::transaction(function () use ($request, $driver) {
            $user = $driver->user;
            $normalizeString = function ($string) {
                $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
                $string = preg_replace('/[^a-zA-Z0-9_]/', '_', $string);
                return strtolower($string);
            };

            $oldNameSlug = $normalizeString($user->name);
            $oldFolder = "drivers/{$user->id}/{$oldNameSlug}";
            $oldFolderPath = storage_path("app/public/{$oldFolder}");

            $user->name  = $request->name;
            $user->email = $request->email;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            $newNameSlug = $normalizeString($user->name);
            $newFolder = "drivers/{$user->id}/{$newNameSlug}";
            $newFolderPath = storage_path("app/public/{$newFolder}");

            Log::info("Updating driver ID {$user->id} from {$oldFolder} to {$newFolder}");

            // 🔹 Primero renombramos la carpeta antes de manejar la foto
            if ($oldNameSlug !== $newNameSlug) {
                if (file_exists($oldFolderPath) && is_dir($oldFolderPath)) {
                    Log::info("Old folder exists: {$oldFolderPath}");

                    if (!file_exists($newFolderPath)) {
                        rename(realpath($oldFolderPath), $newFolderPath);
                        Log::info("Renamed folder {$oldFolder} to {$newFolder}");
                        $driver->photo = str_replace($oldFolder, $newFolder, $driver->photo);
                    } else {
                        Log::error("New folder {$newFolder} already exists.");
                    }
                } else {
                    Log::error("Old folder {$oldFolder} does not exist at {$oldFolderPath}");
                }
            }

            // 🔹 Ahora guardamos la nueva foto en la carpeta renombrada
            if ($request->hasFile('photo')) {
                Log::info("New photo uploaded.");

                $extension = $request->file('photo')->getClientOriginalExtension();
                $filename = "driver_photo.{$extension}";

                // ✅ Crear carpeta si no existe (por seguridad)
                Storage::disk('public')->makeDirectory($newFolder);

                // ✅ Eliminar la foto previa antes de guardar la nueva
                if ($driver->photo && Storage::disk('public')->exists($driver->photo)) {
                    Storage::disk('public')->delete($driver->photo);
                    Log::info("Deleted previous photo: {$driver->photo}");
                }

                // ✅ Guardar nueva foto en la carpeta correcta
                $path = $request->file('photo')->storeAs($newFolder, $filename, 'public');
                Log::info("Stored new photo at: {$path}");

                $driver->photo = $path;
            }

            $driver->license = $request->license;
            $driver->save();

            Log::info("Driver updated successfully for user ID {$user->id}");
        });

        return $request->ajax()
            ? response()->json(['message' => 'Chófer actualizado correctamente'])
            : redirect()->route('drivers.index')->with('success', 'Chófer actualizado correctamente.');
    }

    public function destroy($id)
    {
        $driver = Driver::findOrFail($id);
        $user = $driver->user;

        DB::transaction(function () use ($driver, $user) {
            // 🔹 Obtener la carpeta específica drivers/{user_id}/{name_slug}
            if ($driver->photo) {
                $nameSlug = preg_replace('/[^a-zA-Z0-9_]/', '_', strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $user->name)));
                $folderPath = "drivers/{$user->id}/{$nameSlug}";

                // 🔹 Eliminar solo la carpeta del nombre
                if (Storage::disk('public')->exists($folderPath)) {
                    Storage::disk('public')->deleteDirectory($folderPath);
                    Log::info("Deleted user-specific folder: {$folderPath}");
                } else {
                    Log::warning("Folder not found: {$folderPath}");
                }
            }

            // 🔹 Verificar si drivers/{user_id} está vacío y eliminarla
            $userFolder = "drivers/{$user->id}";
            if (Storage::disk('public')->exists($userFolder) && count(Storage::disk('public')->allFiles($userFolder)) === 0) {
                Storage::disk('public')->deleteDirectory($userFolder);
                Log::info("Deleted empty user ID folder: {$userFolder}");
            }

            // 🔹 Eliminar registros de conductor y usuario
            $driver->delete();
            $user->delete();
        });

        return redirect()->route('drivers.index')->with('success', 'Chófer eliminado correctamente.');
    }
}
