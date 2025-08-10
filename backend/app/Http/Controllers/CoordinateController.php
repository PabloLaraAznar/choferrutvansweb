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
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|string|min:6|confirmed',
            'address'       => 'required|string|max:255',
            'phone_number'  => 'required|string|max:20',
            'site_id'       => 'required|exists:sites,id',
            'photo'         => 'nullable|image|max:2048',
        ]);

        DB::transaction(function () use ($request) {
            // Crear usuario
            $user = User::create([
                'name'              => $request->name,
                'email'             => $request->email,
                'address'           => $request->address,
                'phone_number'      => $request->phone_number,
                'password'          => Hash::make($request->password),
                'email_verified_at' => now(), // <-- Marca como verificado
            ]);

            // Asignar rol de coordinator
            $user->assignRole('coordinate');

            // Obtener último código para ese sitio
            $lastCoordinate = Coordinate::where('site_id', $request->site_id)
                ->orderBy('id', 'desc')
                ->first();

            $nextNumber = 1;
            if ($lastCoordinate && preg_match('/COORD-(\d{4})/', $lastCoordinate->employee_code, $matches)) {
                $nextNumber = intval($matches[1]) + 1;
            }

            $employeeCode = 'COORD-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            // Crear coordinador
            $coordinate = new Coordinate();
            $coordinate->user_id = $user->id;
            $coordinate->employee_code = $employeeCode;
            $coordinate->site_id = $request->site_id;

            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $extension = $file->getClientOriginalExtension();

                $normalizeString = function ($string) {
                    $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
                    return preg_replace('/[^a-zA-Z0-9_]/', '_', strtolower($string));
                };

                $nameSlug = $normalizeString($user->name);
                $folderPath = "coordinators/{$user->id}/{$nameSlug}";
                $filename = "coordinator_photo.{$extension}";

                Storage::disk('public')->makeDirectory($folderPath);

                $path = $file->storeAs($folderPath, $filename, 'public');
                $coordinate->photo = $path;
            }

            $coordinate->save();

            // Vincular usuario coordinador al sitio en tabla pivote site_users
            $coordinate->site->users()->syncWithoutDetaching([$user->id]);
        });

        return response()->json(['message' => 'Coordinador creado correctamente.']);
    }

    public function update(Request $request, Coordinate $coordinate)
    {
        $coordinate->load('user');

        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email,' . $coordinate->user->id,
            'password'     => 'nullable|string|min:6|confirmed',
            'address'      => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'photo'        => 'nullable|image|max:2048',
        ]);

        DB::transaction(function () use ($request, $coordinate) {
            $user = $coordinate->user;

            $normalizeString = function ($string) {
                $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
                return preg_replace('/[^a-zA-Z0-9_]/', '_', strtolower($string));
            };

            $oldNameSlug = $normalizeString($user->name);
            $oldFolder = "coordinators/{$user->id}/{$oldNameSlug}";
            $oldFolderPath = storage_path("app/public/{$oldFolder}");

            $user->name         = $request->name;
            $user->email        = $request->email;
            $user->address      = $request->address;
            $user->phone_number = $request->phone_number;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            $newNameSlug = $normalizeString($user->name);
            $newFolder = "coordinators/{$user->id}/{$newNameSlug}";
            $newFolderPath = storage_path("app/public/{$newFolder}");

            if ($oldNameSlug !== $newNameSlug) {
                if (file_exists($oldFolderPath) && is_dir($oldFolderPath)) {
                    if (!file_exists($newFolderPath)) {
                        rename(realpath($oldFolderPath), $newFolderPath);
                        $coordinate->photo = str_replace($oldFolder, $newFolder, $coordinate->photo);
                    }
                }
            }

            if ($request->hasFile('photo')) {
                $extension = $request->file('photo')->getClientOriginalExtension();
                $filename = "coordinator_photo.{$extension}";

                Storage::disk('public')->makeDirectory($newFolder);

                if ($coordinate->photo && Storage::disk('public')->exists($coordinate->photo)) {
                    Storage::disk('public')->delete($coordinate->photo);
                }

                $path = $request->file('photo')->storeAs($newFolder, $filename, 'public');
                $coordinate->photo = $path;
            }

            $coordinate->save();
        });

        return redirect()->route('clients.show', $coordinate->site_id)
            ->with('success', 'Coordinador editado correctamente.');
    }
}
