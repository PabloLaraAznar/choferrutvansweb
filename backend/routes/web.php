<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\{
    CashierController,
    ClientController,
    CompanyController,
    DashboardController,
    HomeController,
    EXCELController,
    PDFController,
    ChatbotController,
    CoordinateController,
    DriverController,
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
    RutaController,
    UserController,
    MetodoPagoController,
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

Route::resource('tarifas', TipoTarifaController::class);



  Route::get('tarifas', [TipoTarifaController::class, 'index'])->name('tarifas.index');
  Route::post('tarifas', [TipoTarifaController::class, 'store'])->name('tarifas.store');
  Route::get('tarifas/{id}/edit', [TipoTarifaController::class, 'edit'])->name('tarifas.edit');
  Route::put('tarifas/{id}', [TipoTarifaController::class, 'update'])->name('tarifas.update');
  Route::put('/fare_types/{id}', [TipoTarifaController::class, 'update'])->name('fare_types.update');

  Route::delete('tarifas/{id}', [TipoTarifaController::class, 'destroy'])->name('tarifas.destroy');

Route::post('/chatbot/handle', [ChatbotController::class, 'handle'])->name('chatbot.handle');

/*
|--------------------------------------------------------------------------
| Email Verification Routes (Fuera del middleware verified para evitar bucles)
|--------------------------------------------------------------------------
*/
// Ruta para ver página de "verifica tu correo"
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// Ruta para manejar el clic en el enlace de verificación sin requerir autenticación
Route::get('/email/verify/{id}/{hash}', function ($id, $hash) {
    $user = User::findOrFail($id);

    // Validar que el hash coincida con el email del usuario
    if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        abort(403);
    }

    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
    }

    return redirect('/login?verified=1'); // Redirige al login con parámetro de verificación exitosa
})->middleware('signed')->name('verification.verify');

// Ruta para reenviar el email de verificación (requiere autenticación)
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Correo de verificación reenviado.');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

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

    // Dashboard principal - Solo admin
    Route::middleware(['auth:sanctum', 'verified'])->group(function () {
        Route::get('/dashboard', [HomeController::class, 'index'])
            ->name('dashboard')
            ->middleware('can:admin'); // Solo admin puede ver dashboard
    });

    // Dashboard dinámico según rol
    Route::middleware(['auth:sanctum', 'verified'])->group(function () {
        Route::get('/dashboard-role', [DashboardController::class, 'index'])->name('dashboard.role');
    });

    /*
    |--------------------------------------------------------------------------
    | CRUDs - Recursos protegidos (Solo Super-Admin)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'can:super-admin'])->group(function () {
        Route::resource('roles', RolesController::class); // Middleware deshabilitado temporalmente para pruebas
        Route::resource('permissions', PermissionsController::class);
        Route::resource('companies', CompanyController::class); // Empresas/Sindicatos
        Route::resource('clients', ClientController::class); // Sitios/Rutas (renombrar después)
    });

    /*
    |--------------------------------------------------------------------------
    | Roles y Permisos - Asignación (Solo Super-Admin)
    |--------------------------------------------------------------------------
    */
    Route::middleware('can:super-admin')->group(function () {
        Route::get('/roles-permissions', [RolesPermissionsController::class, 'index'])->name('roles-permissions.index');
        Route::get('/roles-permissions/{role}/edit', [RolesPermissionsController::class, 'edit'])->name('roles-permissions.edit');
        Route::put('/roles-permissions/{role}', [RolesPermissionsController::class, 'update'])->name('roles-permissions.update');
        
        // Exportaciones - PDF / Excel (Solo Super-Admin)
        Route::get('exports/pdf/localidades', [PDFController::class, 'expLocalidades'])->name('exports.pdf.localidades');
        Route::get('exports/excel/localidades', [EXCELController::class, 'expLocalidades'])->name('exports.excel.localidades');
    });

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
    | Vista tabla de localidades (Solo Super-Admin)
    |--------------------------------------------------------------------------
    */
    Route::middleware('can:super-admin')->group(function () {
        Route::resource('localidades', LocalidadesController::class);
        Route::get('/localidades-exp', [LocExpController::class, 'index'])->name('localidades-exp.index');
        Route::post('/localidades-exp/data', [LocExpController::class, 'getLocalidades'])->name('localidades-exp.data');
        Route::get('/localidades-debug', [LocalidadesController::class, 'debug'])->name('localidades.debug');
    });

    /*
    |--------------------------------------------------------------------------
    | Vista de unidades con asignacion a choferes (Admin y Coordinate)
    |--------------------------------------------------------------------------
    */
    Route::middleware('can:admin-coordinate')->group(function () {
        Route::resource('horarios', HorarioController::class);
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
    });

    /*
    |--------------------------------------------------------------------------
    | Calendario de Horarios (Admin y Coordinate)
    |--------------------------------------------------------------------------
    */
    Route::middleware('can:admin-coordinate')->group(function () {
        Route::get('/route-unit-schedule', [RouteUnitScheduleController::class, 'index'])->name('route_unit_schedule.index');
        Route::get('/route-unit-schedule/events', [RouteUnitScheduleController::class, 'getEvents'])->name('route_unit_schedule.events');
        Route::post('/route-unit-schedule', [RouteUnitScheduleController::class, 'store'])->name('route_unit_schedule.store');
        Route::put('/route-unit-schedule/{id}', [RouteUnitScheduleController::class, 'update'])->name('route_unit_schedule.update');
        Route::delete('/route-unit-schedule/{id}', [RouteUnitScheduleController::class, 'destroy'])->name('route_unit_schedule.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Vistas de creacion de empleados (Admin y Coordinate)
    |--------------------------------------------------------------------------
    */
    Route::middleware('can:admin-coordinate')->group(function () {
        Route::resource('drivers', DriverController::class);
        Route::resource('coordinates', CoordinateController::class);
        Route::resource('cashiers', CashierController::class);
    });
    /*
    |--------------------------------------------------------------------------
    | Rutas Sales (Admin y Coordinate)
    |--------------------------------------------------------------------------
    */
    Route::middleware('can:admin-coordinate')->group(function () {
        Route::get('/sales/history', [SalesHistoryController::class, 'index'])->name('sales.history');
        Route::post('/sales/by-date', [SalesHistoryController::class, 'getSalesByDate']);
    });

    /*
    |--------------------------------------------------------------------------
    | Vistas Livewire personalizadas (Admin y Coordinate)
    |--------------------------------------------------------------------------
    */
    Route::middleware('can:admin-coordinate')->group(function () {
        Route::get('/ventas', fn() => view('Ventas.ventas'))->name('ventas.index');
        Route::get('/unidades', fn() => view('Unidades.unidades'))->name('unidades.index');
        Route::get('/envios', fn() => view('Envios.envios'))->name('envios.index');
        Route::get('/conductores', fn() => view('Conductores.conductores'))->name('conductores.index');
        Route::get('/tipos-tarifas', fn() => view('tipoTarifas.tipoTarifas'))->name('tipotarifas.index');
        Route::get('/destino-intermedio', fn() => view('Destino_intermedio.destino_intermedio'))->name('destino-intermedio.index');
    });

    /*
    |--------------------------------------------------------------------------
    | Venta (Admin y Coordinate)
    |--------------------------------------------------------------------------
    */
    Route::middleware('can:admin-coordinate')->group(function () {
        Route::get('/venta', fn() => view('Ventas.venta'))->name('venta.index');
    });

    /*
    |--------------------------------------------------------------------------
    | Rutas de transporte (Admin y Coordinate)
    |--------------------------------------------------------------------------
    */
    Route::middleware('can:admin-coordinate')->group(function () {
        Route::resource('rutas', RutaController::class);
    });

    /*
    |--------------------------------------------------------------------------
    | Usuarios (Admin y Coordinate)
    |--------------------------------------------------------------------------
    */
    Route::middleware('can:admin-coordinate')->group(function () {
        Route::resource('usuarios', UserController::class);
    });

    /*
    |--------------------------------------------------------------------------
    | Métodos de Pago - CRUD (Admin y Coordinate)
    |--------------------------------------------------------------------------
    */
    Route::middleware('can:admin-coordinate')->group(function () {
        Route::resource('metodoPago', MetodoPagoController::class)->names([
            'index' => 'metodoPago.index',
            'store' => 'metodoPago.store',
            'update' => 'metodoPago.update',
            'destroy' => 'metodoPago.destroy',
        ]);
    });
});
