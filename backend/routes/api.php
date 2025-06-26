<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


use App\Http\Controllers\EnvioController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\LocalidadesController;

Route::get('/envios', [EnvioController::class, 'apiIndex']);
Route::get('/horarios', [HorarioController::class, 'apiIndex']);
Route::get('/localidades', [LocalidadesController::class, 'apiIndex']);
