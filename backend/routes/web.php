<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    EXCELController,
    PDFController,
    ChatbotController,
    RolesController,
    PermissionsController,
    RolesPermissionsController,
    LocalidadesController,
    LocExpController,
    TipoTarifaController,
    HorarioController
};

use App\Livewire\{
    VentaComponent,
    LocalidadComponent,
    HorarioComponent,
    EnvioComponent,
    UnidadComponent,
    ConductorComponent,
    TipoTarifaComponent,
    DestinoIntermedioComponent,
    RutaComponent
};

/*
|--------------------------------------------------------------------------
| Rutas públicas (sin autenticación)
|--------------------------------------------------------------------------
*/

// Redirigir “/” directamente al login
Route::get('/', function () {
    return redirect()->route('login');
});


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
    | CRUDs - Recursos protegidos
    |--------------------------------------------------------------------------
    */
    Route::resource('roles', RolesController::class);
    Route::resource('permissions', PermissionsController::class);
    Route::resource('horarios', HorarioController::class);
    Route::resource('localidades', LocalidadesController::class);

    /*
    |--------------------------------------------------------------------------
    | Roles y Permisos - Asignación
    |--------------------------------------------------------------------------
    */
    Route::get('/roles-permissions', [RolesPermissionsController::class, 'index'])->name('roles-permissions.index');
    Route::get('/roles-permissions/{role}/edit', [RolesPermissionsController::class, 'edit'])->name('roles-permissions.edit');
    Route::put('/roles-permissions/{role}', [RolesPermissionsController::class, 'update'])->name('roles-permissions.update');

    /*
    |--------------------------------------------------------------------------
    | Exportaciones - PDF / Excel
    |--------------------------------------------------------------------------
    */
    Route::get('exports/pdf/localidades', [PDFController::class, 'expLocalidades'])->name('exports.pdf.localidades');
    Route::get('exports/excel/localidades', [EXCELController::class, 'localidades'])->name('exports.excel.localidades');

    /*
    |--------------------------------------------------------------------------
    | Vista tabla de localidades (Livewire + Controller)
    |--------------------------------------------------------------------------
    */
    Route::get('/localidades-exp', [LocExpController::class, 'index'])->name('localidades-exp.index');
    Route::post('/localidades-exp/data', [LocExpController::class, 'getLocalidades'])->name('localidades-exp.data');

    /*
    |--------------------------------------------------------------------------
    | Vistas Livewire personalizadas
    |--------------------------------------------------------------------------
    */
    Route::get('/ventas', fn () => view('Ventas.ventas'))->name('ventas.index');
    Route::get('/unidades', fn () => view('Unidades.unidades'))->name('unidades.index');
    Route::get('/envios', fn () => view('Envios.envios'))->name('envios.index');
    Route::get('/conductores', fn () => view('Conductores.conductores'))->name('conductores.index');
    Route::get('/tipos-tarifas', fn () => view('tipoTarifas.tipoTarifas'))->name('tipotarifas.index');
    Route::get('/destino-intermedio', fn () => view('Destino_intermedio.destino_intermedio'))->name('destino-intermedio.index');
    Route::get('/ruta', fn () => view('Ruta.ruta'))->name('ruta.index');

});
