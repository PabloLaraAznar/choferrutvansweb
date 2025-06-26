<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController,
    EXCELController,
    PDFController,
    ChatbotController,
    RolesController,
    PermissionsController,
    RolesPermissionsController,
    LocalidadesController,
    LocExpController,
    TipoTarifaController,
    HorarioController,
    RouteUnitScheduleController,
    RutasUnidadesController,
    SalesHistoryController,
    ProfileController,
    UnitController,
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

Route::post('/chatbot/handle', [ChatbotController::class, 'handle'])->name('chatbot.handle');
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
    Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
});

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
    | Perfil de usuario
    |--------------------------------------------------------------------------
    */
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/perfil/password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
    Route::post('/logout-other-sessions', [ProfileController::class, 'logoutOtherSessions'])->name('logout.other.sessions');
    Route::get('/profile/sessions', [ProfileController::class, 'showSessions'])->middleware('auth')->name('sessions.index');
    Route::delete('/profile/sessions/{id}', [ProfileController::class, 'destroySession'])->middleware('auth')->name('sessions.destroy');
    Route::post('/profile/verify-password', [ProfileController::class, 'verifyPassword'])->name('profile.verify-password');
    Route::post('/profile/delete-account', [ProfileController::class, 'eliminarUsuario'])->name('profile.eliminar');


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
    | Vista de unidades con asignacion a choferes
    |--------------------------------------------------------------------------
    */
    Route::resource('rutas-unidades', RutasUnidadesController::class)->except(['show']);
    Route::post('rutas-unidades/asignar', [RutasUnidadesController::class, 'store'])->name('rutaunidad.store');
    Route::delete('rutas-unidades/{id}', [RutasUnidadesController::class, 'destroy'])->name('rutaunidad.destroy');
    Route::put('rutas-unidades/{id}', [RutasUnidadesController::class, 'update'])->name('rutaunidad.update');
    //EXPORTACION PDF Y EXCEL DE UNIDADES
    Route::get('units/unitsexportexcel', [EXCELController::class, 'expUnidades'])->name('exports.unitsexportexcel');
    Route::get('units/unitsexportpdf', [PDFController::class, 'expUnidades'])->name('exports.unitsexportpdf');
    Route::resource('units', UnitController::class)->except(['show']);
    Route::post('units/{unit}/assign-driver', [UnitController::class, 'assignDriver'])->name('units.assignDriver');
    Route::delete('units/{unit}/remove-driver/{driver}', [UnitController::class, 'removeDriver'])->name('units.removeDriver');
    
    /*
    |--------------------------------------------------------------------------
    | Calendario de Horarios
    |--------------------------------------------------------------------------
    */
    Route::get('/route-unit-schedule', [RouteUnitScheduleController::class, 'index'])->name('route_unit_schedule.index');
    Route::get('/route-unit-schedule/events', [RouteUnitScheduleController::class, 'getEvents'])->name('route_unit_schedule.events');
    Route::post('/route-unit-schedule', [RouteUnitScheduleController::class, 'store'])->name('route_unit_schedule.store');
    Route::put('/route-unit-schedule/{id}', [RouteUnitScheduleController::class, 'update'])->name('route_unit_schedule.update');
    Route::delete('/route-unit-schedule/{id}', [RouteUnitScheduleController::class, 'destroy'])->name('route_unit_schedule.destroy');

    /*
    |--------------------------------------------------------------------------
    | Rutas Sales
    |--------------------------------------------------------------------------
    */
    Route::get('/sales/history', [SalesHistoryController::class, 'index'])->name('sales.history');
    Route::post('/sales/by-date', [SalesHistoryController::class, 'getSalesByDate']);

    
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
   

});

use App\Http\Controllers\RutaController;
Route::resource('rutas',RutaController::class);

