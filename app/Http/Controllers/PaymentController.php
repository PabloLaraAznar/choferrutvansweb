<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Payment;

class PaymentController extends Controller
{
    // Store a new payment
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reservation_id' => 'required|integer|exists:reservations,id',
            'amount' => 'required|numeric',
            'method' => 'required|string',
            'status' => 'required|string',
        ]);

        $payment = Payment::create($validated);

        return response()->json([
            'success' => true,
            'payment' => $payment
        ], 201);
    }

    // Optionally, fetch payments
    public function index()
    {
        $payments = Payment::all();
        return response()->json($payments);
    }
}
