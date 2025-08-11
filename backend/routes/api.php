<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


use App\Http\Controllers\EnvioController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\LocalidadesController;

Route::get('/envios', [EnvioController::class, 'apiIndex']);
Route::get('/horarios', [HorarioController::class, 'apiIndex']);
Route::get('/localidades', [LocalidadesController::class, 'apiIndex']);

use App\Http\Controllers\API\SuperAdmin\AdminController;
use App\Http\Controllers\API\SuperAdmin\CompanyController;
use App\Http\Controllers\API\SuperAdmin\SiteController;
use App\Http\Controllers\API\SuperAdmin\UnitController;
use App\Http\Controllers\API\SuperAdmin\CommentController;
use App\Http\Controllers\API\SuperAdmin\FaqController;
use App\Http\Controllers\API\SuperAdmin\FormController;

use App\Http\Controllers\Api\SuperAdmin\ProfileController;
Route::middleware('auth:sanctum')->prefix('super-admin')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::put('/profile/password', [ProfileController::class, 'updatePassword']);
});

// Admin
Route::get('/admin/stats', [AdminController::class, 'stats']);
Route::get('/admin/forms-monthly', [AdminController::class, 'formsMonthly']);
Route::get('/admin/sites-count', [AdminController::class, 'sitesCount']);
Route::get('/admin/units-count', [AdminController::class, 'unitsCount']);
Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);

// Companies
Route::get('/companies/stats', [CompanyController::class, 'stats']); // <-- antes que /companies/{id}
Route::get('/companies', [CompanyController::class, 'index']);
Route::get('/companies/{id}', [CompanyController::class, 'show']);

// Sites
Route::get('/sites', [SiteController::class, 'index']);
Route::get('/sites/{id}', [SiteController::class, 'show']);

// Units
Route::get('/units', [UnitController::class, 'index']);
Route::get('/units/{id}', [UnitController::class, 'show']);

// Comentarios API
Route::get('/comments', [CommentController::class, 'index']);
Route::post('/comments', [CommentController::class, 'store']);
Route::put('/comments/{id}', [CommentController::class, 'update']);
Route::delete('/comments/{id}', [CommentController::class, 'destroy']);

// FAQs API
Route::get('/faqs', [FaqController::class, 'index']);
Route::post('/faqs', [FaqController::class, 'store']);
Route::get('/faqs/{id}', [FaqController::class, 'show']);
Route::put('/faqs/{id}', [FaqController::class, 'update']);
Route::delete('/faqs/{id}', [FaqController::class, 'destroy']);
Route::patch('/faqs/{id}', [FaqController::class, 'update']);

// Cotizaciones (Formularios de Cotización)
Route::get('/forms', [FormController::class, 'index']);   
Route::post('/forms', [FormController::class, 'store']);  


use App\Models\User;
use App\Http\Resources\UserResource;

Route::post('super-admin/mobile-login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Credenciales inválidas'], 401);
    }

    if (!$user->hasVerifiedEmail()) {
        return response()->json(['message' => 'Debes verificar tu email antes de iniciar sesión.'], 403);
    }

    $token = $user->createToken('mobile')->plainTextToken;

    // Asegura que los roles estén cargados
    $user->load('roles');

    return response()->json([
        'token' => $token,
        'user' => new UserResource($user), // Usar Resource aquí
    ]);
});

Route::post('super-admin/mobile-register', function (Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|string|min:6',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);
    // Asigna el rol 'client'
    $user->assignRole('client');

    $token = $user->createToken('mobile')->plainTextToken;

    $user->load('roles');

    return response()->json([
        'token' => $token,
        'user' => new UserResource($user),
    ], 201);
});

