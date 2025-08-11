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

use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\SiteController;
use App\Http\Controllers\API\UnitController;

// Admin routes
Route::get('/admin/stats', [AdminController::class, 'stats']);
Route::get('/admin/forms-monthly', [AdminController::class, 'formsMonthly']);
Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
Route::get('/admin/sites-count', [AdminController::class, 'sitesCount']);
Route::get('/admin/units-count', [AdminController::class, 'unitsCount']);
Route::get('/admin/sites', [AdminController::class, 'sites']);

// Companies
Route::get('/companies', [CompanyController::class, 'index']);
Route::get('/companies/{id}', [CompanyController::class, 'show']);
Route::get('/companies/stats', [CompanyController::class, 'stats']);

// Sites
Route::get('/sites', [SiteController::class, 'index']);
Route::get('/sites/{id}', [SiteController::class, 'show']);

// Units
Route::get('/units', [UnitController::class, 'index']);
Route::get('/units/{id}', [UnitController::class, 'show']);