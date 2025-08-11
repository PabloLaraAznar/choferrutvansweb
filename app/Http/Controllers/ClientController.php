<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\Company;
use App\Models\User;
use App\Models\Locality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    public function __construct()
    {
        // Solo usuarios con rol super-admin pueden acceder
        $this->middleware(['auth', 'verified']);
        $this->middleware('can:manage-companies-and-sites');
    }

    public function index(Request $request)
    {
        $query = Site::with(['locality', 'users', 'company']);
        
        // Filtrar por empresa si se especifica
        if ($request->has('company') && $request->company != '') {
            $query->where('company_id', $request->company);
        }
        
        // Filtrar por estado si se especifica
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        $sites = $query->orderBy('name')->paginate(10);
        $companies = Company::where('status', 'active')->orderBy('name')->get();
        $localities = Locality::orderBy('locality')->get();
        
        // Obtener usuarios con rol admin para poder asignarlos a sitios
        $availableAdmins = User::whereHas('roles', function($query) {
            $query->where('name', 'admin');
        })->orderBy('name')->get();
        
        return view('rutvans.sites.index', compact('sites', 'companies', 'localities', 'availableAdmins'));
    }

    public function store(Request $request)
    {
        $validationRules = [
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:255',
            'route_name' => 'nullable|string|max:255', // Ruta principal opcional
            'locality_id' => 'required|exists:localities,id',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            'status' => 'required|in:active,inactive',
            'admin_type' => 'required|in:existing,new',
        ];

        // Agregar validaciones condicionales según el tipo de admin
        if ($request->input('admin_type') === 'existing') {
            $validationRules['existing_admin_id'] = 'required|exists:users,id';
        } else {
            $validationRules['admin_name'] = 'required|string|max:255';
            $validationRules['admin_email'] = 'required|email|unique:users,email';
            $validationRules['admin_password'] = 'required|string|min:6|confirmed';
        }

        $request->validate($validationRules);

        DB::transaction(function () use ($request) {
            // Crear el sitio/terminal
            $site = Site::create([
                'company_id' => $request->company_id,
                'name' => $request->name,
                'route_name' => $request->route_name, // Ruta principal opcional
                'locality_id' => $request->locality_id,
                'address' => $request->address,
                'phone' => $request->phone,
                'status' => $request->status,
            ]);

            // Seleccionar o crear admin
            if ($request->admin_type === 'existing') {
                $user = User::find($request->existing_admin_id);
            } else {
                // Crear nuevo usuario admin
                $user = User::create([
                    'name' => $request->admin_name,
                    'email' => $request->admin_email,
                    'password' => Hash::make($request->admin_password),
                    'email_verified_at' => now(),
                ]);

                // Asignar rol de admin al usuario
                $user->assignRole('admin');
            }

            // Asociar el usuario al sitio como admin
            $user->sites()->attach($site->id, ['role' => 'admin']);
        });

        return redirect()->route('clients.index')
            ->with('success', 'Sitio/Terminal creado exitosamente.');
    }

    public function update(Request $request, Site $client)
    {
        // Obtener el usuario admin principal del sitio
        $adminUser = $client->users()
            ->wherePivot('role', 'admin')
            ->first();

        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:255',
            'route_name' => 'nullable|string|max:255', // Ruta principal opcional
            'locality_id' => 'required|exists:localities,id',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            'status' => 'required|in:active,inactive',
            // Seleccionar admin existente o crear nuevo
            'admin_type' => 'required|in:existing,new',
            'existing_admin_id' => 'required_if:admin_type,existing|exists:users,id',
            // Datos del nuevo usuario admin (solo si admin_type = new)
            'admin_name' => 'required_if:admin_type,new|string|max:255',
            'admin_email' => [
                'required_if:admin_type,new',
                'email',
                Rule::unique('users', 'email')->ignore($adminUser?->id),
            ],
            'admin_password' => 'nullable|string|min:6|confirmed',
        ]);

        DB::transaction(function () use ($request, $client, $adminUser) {
            // Actualizar el sitio/terminal
            $client->update([
                'company_id' => $request->company_id,
                'name' => $request->name,
                'route_name' => $request->route_name, // Ruta principal opcional
                'locality_id' => $request->locality_id,
                'address' => $request->address,
                'phone' => $request->phone,
                'status' => $request->status,
            ]);

            // Manejar admin
            if ($request->admin_type === 'existing') {
                $newAdmin = User::find($request->existing_admin_id);
                
                // Si cambia el admin, desasociar el anterior y asociar el nuevo
                if ($adminUser && $adminUser->id !== $newAdmin->id) {
                    $client->users()->detach($adminUser->id);
                    $client->users()->attach($newAdmin->id, ['role' => 'admin']);
                } elseif (!$adminUser) {
                    // Si no había admin, asociar el nuevo
                    $client->users()->attach($newAdmin->id, ['role' => 'admin']);
                }
            } else {
                // Crear o actualizar admin
                if ($adminUser) {
                    $userData = [
                        'name' => $request->admin_name,
                        'email' => $request->admin_email,
                    ];

                    // Solo actualizar password si se proporciona uno nuevo
                    if ($request->filled('admin_password')) {
                        $userData['password'] = Hash::make($request->admin_password);
                    }

                    $adminUser->update($userData);
                } else {
                    // Crear nuevo usuario admin
                    $user = User::create([
                        'name' => $request->admin_name,
                        'email' => $request->admin_email,
                        'password' => Hash::make($request->admin_password),
                        'email_verified_at' => now(),
                    ]);

                    // Asignar rol de admin al usuario
                    $user->assignRole('admin');
                    
                    // Asociar el usuario al sitio como admin
                    $user->sites()->attach($client->id, ['role' => 'admin']);
                }
            }
        });

        return redirect()->route('clients.index')
            ->with('success', 'Sitio/Terminal actualizado exitosamente.');
    }

    public function destroy(Site $client)
    {
        DB::transaction(function () use ($client) {
            // Obtener usuarios del sitio antes de eliminar las relaciones
            $users = $client->users()->get();
            
            // Desasociar usuarios del sitio
            $client->users()->detach();
            
            // Verificar si hay usuarios que solo pertenecían a este sitio
            // y eliminarlos si no tienen otros sitios
            foreach ($users as $user) {
                if ($user->sites()->count() === 0) {
                    $user->delete();
                }
            }
            
            // Eliminar el sitio
            $client->delete();
        });

        return redirect()->route('clients.index')
            ->with('success', 'Sitio/Terminal eliminado exitosamente.');
    }

    public function show(Site $client)
    {
        $client->load(['locality', 'users', 'company']);
        
        // Estadísticas simples para evitar errores con modelos inexistentes
        $stats = [
            'drivers' => $client->users()->whereHas('roles', function($query) {
                $query->where('name', 'driver');
            })->count(),
            'cashiers' => $client->users()->whereHas('roles', function($query) {
                $query->where('name', 'cashier');
            })->count(),
            'coordinates' => 0, // Placeholder para futuras implementaciones
            'units' => 0, // Placeholder para futuras implementaciones
        ];
        
        return response()->json([
            'site' => $client,
            'stats' => $stats
        ]);
    }
}
