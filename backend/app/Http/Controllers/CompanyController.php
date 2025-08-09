<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\Locality;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('can:manage-companies-and-sites');
    }

    public function index()
    {
        $companies = Company::with(['locality', 'sites'])->paginate(10);
        $localities = Locality::orderBy('locality')->get();
        
        return view('rutvans.companies.index', compact('companies', 'localities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'business_name' => 'nullable|string|max:255',
            'rfc' => 'nullable|string|max:13',
            'locality_id' => 'nullable|exists:localities,id',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
            // Datos del usuario admin principal
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users,email',
            'admin_password' => 'required|string|min:6|confirmed',
        ]);

        DB::transaction(function () use ($request) {
            // Crear la empresa/sindicato
            $company = Company::create($request->only([
                'name', 'business_name', 'rfc', 'locality_id', 
                'address', 'phone', 'email', 'status', 'notes'
            ]));

            // Crear un sitio principal para la empresa
            $mainSite = $company->sites()->create([
                'name' => 'Sede Principal',
                'locality_id' => $request->locality_id,
                'address' => $request->address,
                'phone' => $request->phone,
                'status' => 'active'
            ]);

            // Crear el usuario admin principal
            $user = User::create([
                'name' => $request->admin_name,
                'email' => $request->admin_email,
                'password' => Hash::make($request->admin_password),
                'email_verified_at' => now(),
            ]);

            // Asignar rol de admin en el sistema
            $user->assignRole('admin');

            // Vincular el usuario como dueño de la compañía
            CompanyUser::create([
                'company_id' => $company->id,
                'user_id' => $user->id,
                'role' => 'admin',
                'status' => 'active'
            ]);
        });

        return redirect()->route('companies.index')
            ->with('success', 'Empresa/Sindicato creado exitosamente.');
    }

    public function show(Company $company)
    {
        $company->load(['locality']);
        
        return response()->json([
            'company' => $company
        ]);
    }

    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'business_name' => 'nullable|string|max:255',
            'rfc' => 'nullable|string|max:13',
            'locality_id' => 'nullable|exists:localities,id',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ]);

        $company->update($request->only([
            'name', 'business_name', 'rfc', 'locality_id', 
            'address', 'phone', 'email', 'status', 'notes'
        ]));

        return redirect()->route('companies.index')
            ->with('success', 'Empresa/Sindicato actualizado exitosamente.');
    }

    public function destroy(Company $company)
    {
        DB::transaction(function () use ($company) {
            // Eliminar todos los sitios de la empresa (cascade eliminará usuarios asociados)
            $company->sites()->delete();
            
            // Eliminar la empresa
            $company->delete();
        });

        return redirect()->route('companies.index')
            ->with('success', 'Empresa/Sindicato eliminado exitosamente.');
    }
}
