<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TravelHistory;

class TravelHistoryController extends Controller
{
    public function getRecentTrips(Request $request)
    {
        try {
            $userId = $request->query('user_id');
            if (!$userId) {
                return response()->json(['error' => 'user_id es requerido'], 400);
            }

            $trips = TravelHistory::query()
                ->whereHas('sale', function ($query) use ($userId) {
                    $query->where('id_user', $userId);
                })
                ->with([
                    'sale' => function ($query) {
                        $query->select('id', 'id_user', 'amount');
                    },
                    'routeUnitSchedule' => function ($query) {
                        $query->select('id', 'id_route_unit', 'schedule_date', 'schedule_time');
                    },
                    'routeUnitSchedule.routeUnit' => function ($query) {
                        $query->select('id', 'id_route', 'id_driver_unit');
                    },
                    'routeUnitSchedule.routeUnit.route' => function ($query) {
                        $query->select('id', 'id_location_s', 'id_location_f');
                    },
                    'routeUnitSchedule.routeUnit.route.origin' => function ($query) {
                        $query->select('id', 'locality');
                    },
                    'routeUnitSchedule.routeUnit.route.destination' => function ($query) {
                        $query->select('id', 'locality');
                    },
                    'routeUnitSchedule.routeUnit.driverUnit' => function ($query) {
                        $query->select('id', 'id_driver');
                    },
                    'routeUnitSchedule.routeUnit.driverUnit.driver' => function ($query) {
                        $query->select('id', 'id_user');
                    },
                    'routeUnitSchedule.routeUnit.driverUnit.driver.user' => function ($query) {
                        $query->select('id', 'name');
                    },
                ])
                ->select(
                    'id',
                    'id_sale',
                    'id_route_unit_schedule',
                    'status',
                    'actual_departure as date',
                    'passenger_rating as rating',
                    'report',
                    'created_at',
                    'updated_at'
                )
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get()
                ->map(function ($trip) {
                    return [
                        'id' => $trip->id,
                        'status' => $trip->status,
                        'date' => $trip->date,
                        'rating' => $trip->rating,
                        'report' => $trip->report ?? '',
                        'created_at' => $trip->created_at,
                        'updated_at' => $trip->updated_at,
                        'amount' => $trip->sale->amount,
                        'origin' => $trip->routeUnitSchedule->routeUnit->route->origin->locality,
                        'destination' => $trip->routeUnitSchedule->routeUnit->route->destination->locality,
                        'driver_name' => $trip->routeUnitSchedule->routeUnit->driverUnit->driver->user->name,
                        'time' => $trip->routeUnitSchedule->schedule_time,
                    ];
                });

            return response()->json($trips, 200);
        } catch (\Exception $e) {
            \Log::error('Error en getRecentTrips: ' . $e->getMessage());
            return response()->json(['error' => 'Error al obtener los viajes: ' . $e->getMessage()], 500);
        }
    }

    public function updateTravelRating(Request $request, $id)
    {
        try {
            $request->validate([
                'passenger_rating' => 'nullable|integer|min:0|max:5',
                'report' => 'nullable|string|max:500',
            ]);

            $travel = TravelHistory::findOrFail($id);
            $travel->update([
                'passenger_rating' => $request->input('passenger_rating', null),
                'report' => $request->input('report', ''),
            ]);

            return response()->json(['message' => 'Calificación actualizada con éxito'], 200);
        } catch (\Exception $e) {
            \Log::error('Error en updateTravelRating: ' . $e->getMessage());
            return response()->json(['error' => 'Error al actualizar la calificación: ' . $e->getMessage()], 500);
        }
    }
}