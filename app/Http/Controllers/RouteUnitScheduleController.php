<?php

namespace App\Http\Controllers;

use App\Models\RouteUnitSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class RouteUnitScheduleController extends Controller
{
    public function getRouteUnitSchedules(Request $request)
    {
        $today = Carbon::today();
        Log::info('Fecha actual para filtro: ' . $today);
        Log::info('Parámetros de filtro: origin=' . $request->query('origin') . ', destination=' . $request->query('destination'));

        $query = RouteUnitSchedule::join('route_unit', 'route_unit_schedule.id_route_unit', '=', 'route_unit.id')
            ->join('routes', 'route_unit.id_route', '=', 'routes.id')
            ->join('localities as origin', 'routes.id_location_s', '=', 'origin.id')
            ->join('localities as destination', 'routes.id_location_f', '=', 'destination.id')
            ->join('driver_unit', 'route_unit.id_driver_unit', '=', 'driver_unit.id')
            ->join('drivers', 'driver_unit.id_driver', '=', 'drivers.id')
            ->join('users', 'drivers.id_user', '=', 'users.id')
            ->join('units', 'driver_unit.id_unit', '=', 'units.id')
            ->select(
                'route_unit_schedule.schedule_date',
                'route_unit_schedule.schedule_time',
                \DB::raw("CONCAT(origin.locality, ' - ', destination.locality) as route_unit_description"),
                'users.name as driver_name',
                'units.model as vehicle_model',
                'units.plate as license_plate',
                'units.capacity',
                'units.photo as font_url',
                'route_unit.estimated_duration_seconds',
                'units.id as unit_id'
            )
            ->where('route_unit_schedule.schedule_date', '=', $today);

        // Aplicar filtros de origen y destino si se proporcionan
        if ($request->has('origin')) {
            $query->where('origin.locality', $request->query('origin'));
        }
        if ($request->has('destination')) {
            $query->where('destination.locality', $request->query('destination'));
        }

        $schedules = $query->orderBy('route_unit_schedule.schedule_time', 'asc')->get();

        Log::info('Horarios obtenidos: ' . $schedules->toJson());

        return response()->json($schedules, 200);
    }
}