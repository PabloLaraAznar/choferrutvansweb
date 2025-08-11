<?php

namespace App\Http\Controllers;

use App\Models\Coordinate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CoordinateController extends Controller
{
    public function index()
    {
        // Get current user's sites
        $userSites = Auth::user()->sites->pluck('id');
        
        // Filter coordinators by sites the user has access to
        $coordinators = Coordinate::with('user')
            ->whereIn('site_id', $userSites)
            ->get();
            
        return view('empleados.coordinates.index', compact('coordinators'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'photo'    => 'nullable|image|max:2048',
        ]);

        DB::transaction(function () use ($request) {
            // Crear usuario
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Asignar rol de coordinator
            $user->assignRole('coordinator');

            // Generar el código de empleado automáticamente
            $lastCoordinate = Coordinate::latest('id')->first();
            $employeeCode = $lastCoordinate ? str_pad($lastCoordinate->id + 1, 4, '0', STR_PAD_LEFT) : '0001';

            // Crear coordinador con ID de usuario recién creado
            $coordinate = new Coordinate();
            $coordinate->id_user = $user->id;
            $coordinate->employee_code = $employeeCode;
            
            // Assign to current user's first site
            $userSites = Auth::user()->sites;
            if ($userSites->count() > 0) {
                $coordinate->site_id = $userSites->first()->id;
            }

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
                $folderPath = "coordinators/{$user->id}/{$nameSlug}";
                $filename = "coordinator_photo.{$extension}";

                // Crear carpeta si no existe
                Storage::disk('public')->makeDirectory($folderPath);

                // Guardar la foto en la estructura especificada
                $path = $file->storeAs($folderPath, $filename, 'public');

                $coordinate->photo = $path;
            }

            $coordinate->save();
        });

        return redirect()->route('coordinates.index')->with('success', 'Coordinador creado correctamente.');
    }

    public function update(Request $request, Coordinate $coordinate)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $coordinate->user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'photo'    => 'nullable|image|max:2048',
        ]);

        DB::transaction(function () use ($request, $coordinate) {
            $user = $coordinate->user;

            // Función para normalizar el nombre
            $normalizeString = function ($string) {
                $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
                return preg_replace('/[^a-zA-Z0-9_]/', '_', strtolower($string));
            };

            $oldNameSlug = $normalizeString($user->name);
            $oldFolder = "coordinators/{$user->id}/{$oldNameSlug}";
            $oldFolderPath = storage_path("app/public/{$oldFolder}");

            // Actualizar datos del usuario
            $user->name  = $request->name;
            $user->email = $request->email;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            $newNameSlug = $normalizeString($user->name);
            $newFolder = "coordinators/{$user->id}/{$newNameSlug}";
            $newFolderPath = storage_path("app/public/{$newFolder}");

            Log::info("Updating coordinator ID {$user->id} from {$oldFolder} to {$newFolder}");

            // 🔹 Renombrar carpeta si el nombre cambió
            if ($oldNameSlug !== $newNameSlug) {
                if (file_exists($oldFolderPath) && is_dir($oldFolderPath)) {
                    Log::info("Old folder exists: {$oldFolderPath}");

                    if (!file_exists($newFolderPath)) {
                        rename(realpath($oldFolderPath), $newFolderPath);
                        Log::info("Renamed folder {$oldFolder} to {$newFolder}");
                        $coordinate->photo = str_replace($oldFolder, $newFolder, $coordinate->photo);
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
                $filename = "coordinator_photo.{$extension}";

                // ✅ Crear carpeta si no existe
                Storage::disk('public')->makeDirectory($newFolder);

                // ✅ Eliminar la foto previa antes de guardar la nueva
                if ($coordinate->photo && Storage::disk('public')->exists($coordinate->photo)) {
                    Storage::disk('public')->delete($coordinate->photo);
                    Log::info("Deleted previous photo: {$coordinate->photo}");
                }

                // ✅ Guardar nueva foto en la carpeta correcta
                $path = $request->file('photo')->storeAs($newFolder, $filename, 'public');
                Log::info("Stored new photo at: {$path}");

                $coordinate->photo = $path;
            }

            $coordinate->save();

            Log::info("Coordinator updated successfully for user ID {$user->id}");
        });

        return $request->ajax()
            ? response()->json(['message' => 'Coordinador actualizado correctamente'])
            : redirect()->route('coordinates.index')->with('success', 'Coordinador actualizado correctamente.');
    }

    public function destroy($id)
    {
        $coordinate = Coordinate::findOrFail($id);
        $user = $coordinate->user;

        DB::transaction(function () use ($coordinate, $user) {
            // 🔹 Obtener la carpeta específica coordinators/{user_id}/{name_slug}
            if ($coordinate->photo) {
                $nameSlug = preg_replace('/[^a-zA-Z0-9_]/', '_', strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $user->name)));
                $folderPath = "coordinators/{$user->id}/{$nameSlug}";

                // 🔹 Eliminar solo la carpeta del nombre
                if (Storage::disk('public')->exists($folderPath)) {
                    Storage::disk('public')->deleteDirectory($folderPath);
                    Log::info("Deleted user-specific folder: {$folderPath}");
                } else {
                    Log::warning("Folder not found: {$folderPath}");
                }
            }

            // 🔹 Verificar si coordinators/{user_id} está vacío y eliminarla
            $userFolder = "coordinators/{$user->id}";
            if (Storage::disk('public')->exists($userFolder) && count(Storage::disk('public')->allFiles($userFolder)) === 0) {
                Storage::disk('public')->deleteDirectory($userFolder);
                Log::info("Deleted empty user ID folder: {$userFolder}");
            }

            // 🔹 Eliminar registros de coordinador y usuario
            $coordinate->delete();
            $user->delete();
        });

        return redirect()->route('coordinates.index')->with('success', 'Coordinador eliminado correctamente.');
    }
}
