<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\Company;
use App\Models\Coordinate;
use App\Models\User;
use App\Models\Locality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        if ($request->has('company') && $request->company != '') {
            $query->where('company_id', $request->company);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $sites = $query->orderBy('name')->paginate(10);
        $companies = Company::where('status', 'active')->orderBy('name')->get();
        $localities = Locality::orderBy('locality')->get();

        // Ya no traemos admins porque no los usamos en creación/edición
        return view('rutvans.sites.index', compact('sites', 'companies', 'localities'));
    }

    public function store(Request $request)
    {
        $validationRules = [
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:255',
            'route_name' => 'nullable|string|max:255',
            'locality_id' => 'required|exists:localities,id',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            'status' => 'required|in:active,inactive',
        ];

        $request->validate($validationRules);

        Site::create([
            'company_id' => $request->company_id,
            'name' => $request->name,
            'route_name' => $request->route_name,
            'locality_id' => $request->locality_id,
            'address' => $request->address,
            'phone' => $request->phone,
            'status' => $request->status,
        ]);

        return redirect()->route('clients.index')
            ->with('success', 'Sitio/Terminal creado exitosamente.');
    }

    public function update(Request $request, Site $client)
    {
        $validationRules = [
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:255',
            'route_name' => 'nullable|string|max:255',
            'locality_id' => 'required|exists:localities,id',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            'status' => 'required|in:active,inactive',
        ];

        $request->validate($validationRules);

        $client->update([
            'company_id' => $request->company_id,
            'name' => $request->name,
            'route_name' => $request->route_name,
            'locality_id' => $request->locality_id,
            'address' => $request->address,
            'phone' => $request->phone,
            'status' => $request->status,
        ]);

        return redirect()->route('clients.index')
            ->with('success', 'Sitio/Terminal actualizado exitosamente.');
    }

    public function destroy(Site $client)
    {
        DB::transaction(function () use ($client) {
            // Desasociar usuarios para evitar problemas (si quieres puedes quitar esta parte)
            $client->users()->detach();

            $client->delete();
        });

        return redirect()->route('clients.index')
            ->with('success', 'Sitio/Terminal eliminado exitosamente.');
    }

    public function show(Site $client)
    {
        $client->load(['locality', 'company', 'users.roles']);

        $coordinators = $client->users->filter(function ($user) {
            return $user->hasRole('coordinate');
        });

        $coordinatorUserId = DB::table('site_users')
            ->where('site_id', $client->id)
            ->where('role', 'coordinator')
            ->value('user_id');

        $assignedCoordinator = null;

        if ($coordinatorUserId) {
            $assignedCoordinator = User::with('roles')
                ->where('id', $coordinatorUserId)
                ->first();

            if ($assignedCoordinator) {
                $coordinateData = Coordinate::where('user_id', $assignedCoordinator->id)->first();

                $assignedCoordinator->employee_code = $coordinateData->employee_code ?? null;
                $assignedCoordinator->photo = $coordinateData->photo ?? null;
                $assignedCoordinator->coordinate_id = $coordinateData->id ?? null; // 👈 necesario para el update
            }
        }

        return view('rutvans.sites.asignar.index', [
            'site' => $client,
            'coordinators' => $coordinators,
            'assignedCoordinator' => $assignedCoordinator,
        ]);
    }



    // public function show(Site $client)
    // {
    //     $client->load(['locality', 'users', 'company']);

    //     $stats = [
    //         'drivers' => $client->users()->whereHas('roles', function ($query) {
    //             $query->where('name', 'driver');
    //         })->count(),
    //         'cashiers' => $client->users()->whereHas('roles', function ($query) {
    //             $query->where('name', 'cashier');
    //         })->count(),
    //         'coordinates' => 0,
    //         'units' => 0,
    //     ];

    //     return response()->json([
    //         'site' => $client,
    //         'stats' => $stats
    //     ]);
    // }
}
