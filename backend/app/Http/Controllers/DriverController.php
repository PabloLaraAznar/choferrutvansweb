<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Driver;
use App\Models\SiteUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

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
        try {
            $validatedData = $request->validate([
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|unique:users,email',
                'password' => 'required|string|min:6|confirmed',
                'license'  => 'required|string|max:100',
                'photo'    => 'nullable|image|max:2048',
            ]);
        } catch (ValidationException $e) {
            if ($request->ajax()) {
                return response()->json(['errors' => $e->errors()], 422);
            }
            throw $e;  // si no es ajax, sigue el flujo normal
        }

        DB::transaction(function () use ($request, &$user, &$driver) {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);
            $user->assignRole('driver');

            $photoPath = null;
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $extension = $file->getClientOriginalExtension();

                $normalizeString = fn($string) =>
                strtolower(preg_replace('/[^a-zA-Z0-9_]/', '_', iconv('UTF-8', 'ASCII//TRANSLIT', $string)));

                $nameSlug = $normalizeString($user->name);
                $folderPath = "drivers/{$user->id}/{$nameSlug}";
                $filename = "driver_photo.{$extension}";

                Storage::disk('public')->makeDirectory($folderPath);
                $photoPath = $file->storeAs($folderPath, $filename, 'public');
                $user->update(['profile_photo_path' => $photoPath]);
            }

            $siteUser = SiteUser::where('user_id', Auth::id())
                ->where('status', 'active')
                ->first();

            $driver = Driver::create([
                'user_id' => $user->id,
                'license' => $request->license,
                'photo'   => $photoPath,
                'site_id' => $siteUser?->site_id,
            ]);

            if ($siteUser) {
                SiteUser::create([
                    'user_id' => $user->id,
                    'site_id' => $siteUser->site_id,
                    'role'    => 'driver',
                    'status'  => 'active',
                ]);
            }
        });

        if ($request->ajax()) {
            return response()->json([
                'message' => 'Chófer creado correctamente.',
                'driver' => $driver,
            ]);
        }

        return redirect()->route('drivers.index')->with('success', 'Chófer creado correctamente.');
    }

    public function update(Request $request, Driver $driver)
    {
        try {
            $request->validate([
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|unique:users,email,' . $driver->user->id,
                'license'  => 'required|string|max:100',
                'password' => 'nullable|string|min:6|confirmed',
                'photo'    => 'nullable|image|max:2048',
            ]);
        } catch (ValidationException $e) {
            if ($request->ajax()) {
                return response()->json(['errors' => $e->errors()], 422);
            }
            throw $e;
        }

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

            if ($oldNameSlug !== $newNameSlug) {
                if (file_exists($oldFolderPath) && is_dir($oldFolderPath)) {
                    if (!file_exists($newFolderPath)) {
                        rename(realpath($oldFolderPath), $newFolderPath);
                        $driver->photo = str_replace($oldFolder, $newFolder, $driver->photo);
                    }
                }
            }

            if ($request->hasFile('photo')) {
                $extension = $request->file('photo')->getClientOriginalExtension();
                $filename = "driver_photo.{$extension}";
                Storage::disk('public')->makeDirectory($newFolder);

                if ($driver->photo && Storage::disk('public')->exists($driver->photo)) {
                    Storage::disk('public')->delete($driver->photo);
                }

                $path = $request->file('photo')->storeAs($newFolder, $filename, 'public');
                $driver->photo = $path;
            }

            $driver->license = $request->license;
            $driver->save();
        });

        if ($request->ajax()) {
            return response()->json(['message' => 'Chófer actualizado correctamente']);
        }

        return redirect()->route('drivers.index')->with('success', 'Chófer actualizado correctamente.');
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
