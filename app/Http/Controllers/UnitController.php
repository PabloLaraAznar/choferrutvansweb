<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UnitController extends Controller
{
    public function show($id)
    {
        $unit = Unit::findOrFail($id);

        return response()->json([
            'id' => $unit->id,
            'model' => $unit->model,
            'plate' => $unit->plate,
            'capacity' => $unit->capacity,
            'photo' => $unit->photo,
            'created_at' => $unit->created_at,
            'updated_at' => $unit->updated_at
        ]);
    }

    public function getOccupiedSeats(Request $request, $unitId)
    {
        $query = Reservation::where('unit_id', $unitId)
            ->whereDate('travel_date', Carbon::today())
            ->where('status', '!=', 'cancelled');

        if ($request->has('schedule_id')) {
            $query->where('route_unit_schedule_id', $request->query('schedule_id'));
        }

        $occupiedSeats = $query->pluck('seats')
            ->flatten()
            ->unique()
            ->values()
            ->toArray();

        return response()->json([
            'occupied_seats' => $occupiedSeats,
            'available_seats' => Unit::find($unitId)->capacity - count($occupiedSeats)
        ]);
    }
}
