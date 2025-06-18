<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EXCELController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\RolesPermissionsController;
use App\Http\Controllers\LocalidadesController;
use App\Http\Controllers\LocExpController;
use App\Http\Controllers\TipoTarifaController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\HorarioController as HorarioCtrl; // Alias si se necesita
use App\Http\Controllers\HorarioController as HorarioControllerAlias;
use App\Http\Controllers\TipoTarifaController as TipoTarifaControllerAlias;

/*
|--------------------------------------------------------------------------
| Rutas públicas (sin autenticación)
|--------------------------------------------------------------------------
*/

// Redirigir “/” directamente al login
Route::get('/', function () {
    return redirect()->route('login');
});

// Chatbot (puede ser público o protegerse según tu lógica)
Route::post('/chatbot', [ChatbotController::class, 'handle'])->name('chatbot.handle');



/*
|--------------------------------------------------------------------------
| Rutas protegidas por autenticación (Jetstream / Sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Dashboard principal
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | CRUD de Roles y Permisos
    |--------------------------------------------------------------------------
    */
    Route::resource('roles', RolesController::class);

    Route::resource('permissions', PermissionsController::class);

    // Rutas para asignación de permisos a roles
    Route::get('/roles-permissions', [RolesPermissionsController::class, 'index'])
        ->name('roles-permissions.index');
    Route::get('/roles-permissions/{role}/edit', [RolesPermissionsController::class, 'edit'])
        ->name('roles-permissions.edit');
    Route::put('/roles-permissions/{role}', [RolesPermissionsController::class, 'update'])
        ->name('roles-permissions.update');


    /*
    |--------------------------------------------------------------------------
    | CRUD de Horarios
    |--------------------------------------------------------------------------
    */
    Route::resource('horarios', HorarioController::class);


    /*
    |--------------------------------------------------------------------------
    | CRUD de Localidades
    |--------------------------------------------------------------------------
    */
    Route::resource('localidades', LocalidadesController::class);
    Route::delete('/localidades/{id}', [LocalidadesController::class, 'destroy'])->name('localidades.destroy');
    


    // Exportar Localidades a PDF
    Route::get('exports/pdf/localidades', [PDFController::class, 'expLocalidades'])
        ->name('exports.pdf.localidades');

    // Exportar Localidades a Excel (EXCELController)
    Route::get('exports/excel/localidades', [EXCELController::class, 'localidades'])
        ->name('exports.excel.localidades');

    /*
    |--------------------------------------------------------------------------
    | Vista y datos de Localidades para DataTables o Excel/CSV
    |--------------------------------------------------------------------------
    */
    Route::get('/localidades-exp', [LocExpController::class, 'index'])
        ->name('localidades-exp.index');
    Route::post('/localidades-exp/data', [LocExpController::class, 'getLocalidades'])
        ->name('localidades-exp.data');


    /*
    |--------------------------------------------------------------------------
    | Otras rutas de ejemplo: Ventas, Unidades, Envios, Conductores, etc.
    |--------------------------------------------------------------------------
    | Estas rutas cargan directamente vistas en “resources/views”. Asegúrate de 
    | que cada vista extienda “layouts.app” (el layout que definimos anteriormente).
    */
    Route::get('/ventas', function () {
        return view('Ventas.ventas');
    })->name('ventas.index');

    Route::get('/unidades', function () {
        return view('Unidades.unidades');
    })->name('unidades.index');

    Route::get('/envios', function () {
        return view('Envios.envios');
    })->name('envios.index');

    Route::get('/conductores', function () {
        return view('Conductores.conductores');
    })->name('conductores.index');

    Route::get('/tipos-tarifas', function () {
        return view('tipoTarifas.tipoTarifas');
    })->name('tipotarifas.index');

    Route::get('/destino-intermedio', function () {
        return view('Destino_intermedio.destino_intermedio');
    })->name('destino-intermedio.index');

    Route::get('/ruta', function () {
        return view('Ruta.ruta');
    })->name('ruta.index');
});
