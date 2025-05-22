<?php

use App\Http\Controllers\EXCELController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatbotController;


//chatbot
Route::post('/chatbot', [ChatbotController::class, 'handle'])->name('chatbot.handle');
Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/', function () {
    return redirect()->route('login');
});


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});


use App\Http\Controllers\RolesController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\RolesPermissionsController;
use App\Http\Controllers\LocalidadesController;
use App\Http\Controllers\LocExpController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\TipoTarifaController;

Route::resource('roles', RolesController::class);
Route::resource('permissions', PermissionsController::class);
Route::get('/roles-permissions/{role}/edit', [RolesPermissionsController::class, 'edit']);
Route::put('/roles-permissions/{role}', [RolesPermissionsController::class, 'update'])->name('roles-permissions.update');
Route::get('/roles-permissions', [RolesPermissionsController::class, 'index'])->name('roles-permissions.index');
Route::resource('localidades', LocalidadesController::class);

Route::get('exports/pdf/localidades', [PDFController::class, 'expLocalidades'])->name('exports.pdf.localidades');
Route::get('exports/excel/localidades', [EXCELController::class, 'localidades'])->name('exports.excel.localidades');
// Ruta para mostrar la tabla de localidades
Route::get('/localidades-exp', [LocExpController::class, 'index'])->name('localidades-exp.index');
Route::post('/localidades-exp/data', [LocExpController::class, 'getLocalidades'])->name('localidades-exp.data');
Route::get('/exports/excel/localidades', [ExcelController::class, 'expLocalidades'])->name('exports.excel.localidades');



use App\Livewire\VentaComponent;
use App\Livewire\LocalidadComponent;
use App\Livewire\HorarioComponent;
use App\Livewire\EnvioComponent;
use App\Livewire\UnidadComponent;
use App\Livewire\ConductorComponent;
use App\Livewire\TipoTarifaComponent;
use App\Livewire\DestinoIntermedioComponent;
use App\Livewire\RutaComponent;


Route::get('/ventas', function () {
    return view('Ventas.ventas');
})->name('ventas.index');

// Route::get('/ventas/data', [VentaComponent::class, 'getVentas'])->name('ventas.data');

// Route::get('/tipotarifa', [TipoTarifaController::class, 'index'])->name('tipotarifa.index');


Route::get('/horarios', function () {
    return view('Horarios.horarios');
})->name('horarios.index');

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
// Ruta para la tabla con DataTables ServerSide
// Route::get('/ventas/data', [VentaController::class, 'getVentas'])->name('ventas.data');
