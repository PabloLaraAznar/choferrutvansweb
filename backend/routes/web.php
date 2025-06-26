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

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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

use App\Http\Controllers\RolesController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\RolesPermissionsController;
use App\Http\Controllers\LocalidadesController;
use App\Http\Controllers\LocExpController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\TipoTarifaController;
use App\Http\Controllers\HorarioController;

Route::resource('roles', RolesController::class);

Route::resource('horarios', HorarioController::class);

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


//Route::get('/horarios', function () {
    //return view('Horarios.horarios');
//})->name('horarios.index');

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

use App\Http\Controllers\UserController;
Route::resource('usuarios', UserController::class);

// use App\Http\Controllers\TipoTarifaController;
// Route::resource('tarifas', TipoTarifaController::class);
