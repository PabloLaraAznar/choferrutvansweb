<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class SaleController extends Controller
{
    /**
     * Obtener los viajes recientes (ventas) de un usuario
     */
    public function recentSales(Request $request)
    {
        $userId = $request->query('user_id');
        if (!$userId) {
            return response()->json(['message' => 'user_id es requerido'], 400);
        }
        $sales = Sale::with(['routeUnitSchedule.routeUnit.route', 'routeUnitSchedule.routeUnit.driverUnit.driver'])
            ->where('id_user', $userId)
            ->orderByDesc('created_at')
            ->limit(3)
            ->get();

        $result = $sales->map(function ($sale) {
            $routeUnitSchedule = $sale->routeUnitSchedule;
            $routeUnit = $routeUnitSchedule ? $routeUnitSchedule->routeUnit : null;
            $route = $routeUnit ? $routeUnit->route : null;
            $driverUnit = $routeUnit ? $routeUnit->driverUnit : null;
            $driver = $driverUnit ? $driverUnit->driver : null;
            return [
                'id' => $sale->id,
                'folio' => $sale->folio,
                'origin' => $route && $route->origin ? $route->origin->locality : null,
                'destination' => $route && $route->destination ? $route->destination->locality : null,
                'date' => $routeUnitSchedule ? $routeUnitSchedule->schedule_date : null,
                'time' => $routeUnitSchedule ? $routeUnitSchedule->schedule_time : null,
                'amount' => $sale->amount,
                'status' => $sale->status,
                'driver' => [
                    'name' => $driver && $driver->user ? $driver->user->name : null,
                    'image' => $driver ? $driver->photo : null,
                    'vehicle' => $routeUnit && $routeUnit->driverUnit && $routeUnit->driverUnit->unit ? $routeUnit->driverUnit->unit->model : null,
                ],
                'rating' => isset($sale->data['rating']) ? $sale->data['rating'] : 0,
                'report' => isset($sale->data['report']) ? $sale->data['report'] : '',
            ];
        });
        return response()->json($result);
    }
    /**
     * Registrar una nueva venta
     */
    public function store(Request $request)
    {
        \Log::info('[SALE] Request recibido', $request->all());

        // Solo validar los campos realmente existentes
        $validator = Validator::make($request->all(), [
            'id_user' => 'required|exists:users,id',
            'id_route_unit_schedule' => 'required|exists:route_unit_schedule,id',
            'id_rate' => 'required|exists:rates,id',
            'data' => 'required|json',
            'amount' => 'required|numeric|min:0',
            'method' => 'required|string',
            'status' => 'nullable|string|in:Pendiente,Completado,Cancelado',
        ]);
        \Log::info('[SALE] Validando solo campos requeridos para sales');

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            $sale = Sale::create([
                'folio' => Sale::generateFolio(),
                'id_user' => $request->id_user,
                'id_route_unit_schedule' => $request->id_route_unit_schedule,
                'id_rate' => $request->id_rate,
                'data' => $request->data,
                'amount' => $request->amount,
                'method' => $request->method,
                'status' => $request->status ?? 'Completado',
            ]);

            return response()->json([
                'message' => 'Venta registrada exitosamente',
                'folio' => $sale->folio,
                'sale' => $sale
            ], 201);

        } catch (\Exception $e) {
            \Log::error('[SALE] Error al registrar la venta: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'message' => 'Error al registrar la venta',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener información de una venta por folio
     */
    public function showByFolio($folio)
    {
        $sale = Sale::with(['user', 'routeUnitSchedule', 'rate'])
                   ->where('folio', $folio)
                   ->first();

        if (!$sale) {
            return response()->json(['message' => 'Venta no encontrada'], 404);
        }

        return response()->json($sale);
    }

    /**
     * Actualizar estado de una venta
     */
    public function updateStatus(Request $request, $folio)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:Pendiente,Completado,Cancelado',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $sale = Sale::where('folio', $folio)->first();

        if (!$sale) {
            return response()->json(['message' => 'Venta no encontrada'], 404);
        }

        $sale->status = $request->status;
        $sale->save();

        return response()->json([
            'message' => 'Estado actualizado correctamente',
            'sale' => $sale
        ]);
    }
}
