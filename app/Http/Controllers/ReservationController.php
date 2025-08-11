<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Unit;
use App\Models\RouteUnitSchedule; // Asegúrate de importar este modelo
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'unit_id' => 'required|exists:units,id',
            'user_id' => 'required|exists:users,id',
            'route_unit_schedule_id' => 'required|exists:route_unit_schedules,id', // Validación añadida
            'seats' => 'required|array',
            'seats.*' => 'integer|min:1|max:50', // Añadido máximo según capacidad común
            'ticket_details' => 'required|array',
            'travel_date' => 'required|date|after_or_equal:today' // Validación de fecha
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Verificar que el horario pertenece a la unidad
        $schedule = RouteUnitSchedule::where('id', $request->route_unit_schedule_id)
                      ->where('unit_id', $request->unit_id)
                      ->first();

        if (!$schedule) {
            return response()->json([
                'message' => 'El horario no corresponde a la unidad seleccionada'
            ], 400);
        }

        // Verificar capacidad
        $unit = Unit::findOrFail($request->unit_id);
        if (count($request->seats) > $unit->capacity) {
            return response()->json([
                'message' => 'La cantidad de asientos excede la capacidad de la unidad'
            ], 400);
        }

        // Verificar asientos ocupados (versión optimizada)
        $occupiedSeats = Reservation::where('unit_id', $request->unit_id)
            ->where('route_unit_schedule_id', $request->route_unit_schedule_id) // Filtro añadido
            ->whereDate('travel_date', Carbon::parse($request->travel_date))
            ->where('status', '!=', 'cancelled')
            ->where(function($query) use ($request) {
                foreach ($request->seats as $seat) {
                    $query->orWhereJsonContains('seats', $seat);
                }
            })
            ->exists();

        if ($occupiedSeats) {
            return response()->json([
                'message' => 'Uno o más asientos ya están ocupados para este viaje',
                'suggestions' => $this->getAvailableSeats($request->unit_id, $request->route_unit_schedule_id, $request->travel_date)
            ], 409);
        }

        // Crear reservación con transacción
        try {
            DB::beginTransaction();

            $reservation = Reservation::create([
                'unit_id' => $request->unit_id,
                'user_id' => $request->user_id,
                'route_unit_schedule_id' => $request->route_unit_schedule_id, // Campo añadido
                'travel_date' => Carbon::parse($request->travel_date),
                'seats' => $request->seats,
                'ticket_details' => $request->ticket_details,
                'total_amount' => $request->ticket_details['total'],
                'status' => 'confirmed'
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Reserva creada exitosamente',
                'data' => $reservation,
                'reservation_id' => $reservation->id
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al crear la reserva',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método auxiliar para obtener asientos disponibles
    protected function getAvailableSeats($unitId, $scheduleId, $travelDate)
    {
        $unit = Unit::find($unitId);
        $allSeats = range(1, $unit->capacity);

        $occupied = Reservation::where('unit_id', $unitId)
            ->where('route_unit_schedule_id', $scheduleId)
            ->whereDate('travel_date', Carbon::parse($travelDate))
            ->where('status', '!=', 'cancelled')
            ->pluck('seats')
            ->flatten()
            ->toArray();

        return array_diff($allSeats, $occupied);
    }

    public function destroy($id)
    {
        try {
            $reservation = Reservation::findOrFail($id);

            // Cambiar estado en lugar de eliminar (mejor práctica)
            $reservation->update(['status' => 'cancelled']);

            return response()->json([
                'message' => 'Reserva cancelada exitosamente',
                'data' => $reservation
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al cancelar la reserva',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
