<?php

namespace App\Http\Controllers;

use App\Models\Cashier;
use App\Models\SiteUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

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
        try {
            $validatedData = $request->validate([
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|unique:users,email',
                'password' => 'required|string|min:6|confirmed',
                'photo'    => 'nullable|image|max:2048',
            ]);
        } catch (ValidationException $e) {
            if ($request->ajax()) {
                return response()->json(['errors' => $e->errors()], 422);
            }
            throw $e;
        }

        try {
            DB::transaction(function () use ($request, &$user, &$cashier) {
                $user = User::create([
                    'name'     => $request->name,
                    'email'    => $request->email,
                    'password' => Hash::make($request->password),
                ]);
                $user->assignRole('cashier');

                $photoPath = null;
                if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
                    $file = $request->file('photo');
                    $extension = $file->getClientOriginalExtension();

                    $normalizeString = fn($string) =>
                    strtolower(preg_replace('/[^a-zA-Z0-9_]/', '_', iconv('UTF-8', 'ASCII//TRANSLIT', $string)));

                    $nameSlug = $normalizeString($user->name);
                    $folderPath = "cashiers/{$user->id}/{$nameSlug}";
                    $filename = "cashier_photo.{$extension}";

                    Storage::disk('public')->makeDirectory($folderPath);
                    $photoPath = $file->storeAs($folderPath, $filename, 'public');
                    $user->update(['profile_photo_path' => $photoPath]);
                }

                $siteUser = SiteUser::where('user_id', Auth::id())
                    ->where('status', 'active')
                    ->first();

                $cashier = Cashier::create([
                    'user_id'       => $user->id,
                    'employee_code' => str_pad(
                        (Cashier::latest('id')->first()?->id ?? 0) + 1,
                        4,
                        '0',
                        STR_PAD_LEFT
                    ),
                    'photo'         => $photoPath,
                    'site_id'       => $siteUser?->site_id,
                ]);

                if ($siteUser) {
                    SiteUser::create([
                        'user_id' => $user->id,
                        'site_id' => $siteUser->site_id,
                        'role'    => 'cashier',
                        'status'  => 'active',
                    ]);
                }
            });
        } catch (\Exception $ex) {
            if ($request->ajax()) {
                return response()->json(['errors' => ['server' => [$ex->getMessage()]]], 500);
            }
            throw $ex;
        }

        if ($request->ajax()) {
            return response()->json([
                'message' => 'Cajero creado correctamente.',
                'cashier' => $cashier,
            ]);
        }

        return redirect()->route('cashiers.index')->with('success', 'Cajero creado correctamente.');
    }

    public function update(Request $request, Cashier $cashier)
    {
        try {
            $request->validate([
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|unique:users,email,' . $cashier->user->id,
                'password' => 'nullable|string|min:6|confirmed',
                'photo'    => 'nullable|image|max:2048',
            ]);
        } catch (ValidationException $e) {
            if ($request->ajax()) {
                return response()->json(['errors' => $e->errors()], 422);
            }
            throw $e;
        }

        DB::transaction(function () use ($request, $cashier) {
            $user = $cashier->user;

            $normalizeString = function ($string) {
                $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
                $string = preg_replace('/[^a-zA-Z0-9_]/', '_', $string);
                return strtolower($string);
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

            // Renombrar carpeta si cambió el nombre
            if ($oldNameSlug !== $newNameSlug) {
                if (file_exists($oldFolderPath) && is_dir($oldFolderPath)) {
                    if (!file_exists($newFolderPath)) {
                        rename(realpath($oldFolderPath), $newFolderPath);
                        $cashier->photo = str_replace($oldFolder, $newFolder, $cashier->photo);
                    }
                }
            }

            // Guardar nueva foto si se subió
            if ($request->hasFile('photo')) {
                $extension = $request->file('photo')->getClientOriginalExtension();
                $filename = "cashier_photo.{$extension}";

                Storage::disk('public')->makeDirectory($newFolder);

                if ($cashier->photo && Storage::disk('public')->exists($cashier->photo)) {
                    Storage::disk('public')->delete($cashier->photo);
                }

                $path = $request->file('photo')->storeAs($newFolder, $filename, 'public');
                $cashier->photo = $path;
            }

            $cashier->save();
        });

        if ($request->ajax()) {
            return response()->json(['message' => 'Cajero actualizado correctamente']);
        }

        return redirect()->route('cashiers.index')->with('success', 'Cajero actualizado correctamente.');
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
