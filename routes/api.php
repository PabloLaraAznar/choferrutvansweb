<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TravelHistoryController;
use App\Http\Controllers\RouteUnitScheduleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ShipmentController;

Route::post('/shipment', [ShipmentController::class, 'index']);
Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::post('/login', [UserController::class, 'login']);
Route::get('/recent-trips', [TravelHistoryController::class, 'getRecentTrips']);
Route::get('/user', [UserController::class, 'getUser']);
Route::patch('/user', [UserController::class, 'updateUser']);
Route::post('/user/upload-photo', [UserController::class, 'uploadPhoto']);
Route::patch('/travel-history/{id}', [TravelHistoryController::class, 'updateTravelRating']);
Route::get('/route-unit-schedules', [RouteUnitScheduleController::class, 'getRouteUnitSchedules']);
Route::get('/ping', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'API funcionando en Laravel 12 con api.php 🧠🔥'
    ]);
});



// Recent Sales (Boletos/Ventas)
Route::get('/sales/recent', [SaleController::class, 'recentSales']);



// Unidades
// Unidades
Route::get('/units/{unit}', [App\Http\Controllers\UnitController::class, 'show']);
Route::get('/units/{unit}/occupied-seats', [App\Http\Controllers\UnitController::class, 'getOccupiedSeats']);

// Rates (tarifas)
Route::get('/rates', [App\Http\Controllers\RateController::class, 'index']);
Route::get('/rates/{id}', [App\Http\Controllers\RateController::class, 'show']);
Route::post('/rates', [App\Http\Controllers\RateController::class, 'store']);
Route::put('/rates/{id}', [App\Http\Controllers\RateController::class, 'update']);
Route::delete('/rates/{id}', [App\Http\Controllers\RateController::class, 'destroy']);


// Reservaciones
Route::post('/reservations', [App\Http\Controllers\ReservationController::class, 'store']);
Route::delete('/reservations/{id}', [App\Http\Controllers\ReservationController::class, 'destroy']);


// Payments
Route::post('/payments', [App\Http\Controllers\PaymentController::class, 'store']);
Route::get('/payments', [App\Http\Controllers\PaymentController::class, 'index']);

// Ventas
Route::post('/sales', [App\Http\Controllers\SaleController::class, 'store']);

