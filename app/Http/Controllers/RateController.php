<?php

namespace App\Http\Controllers;

use App\Models\Rate;
use Illuminate\Http\Request;

class RateController extends Controller
{
    public function index()
    {
        return response()->json(Rate::all(), 200);
    }

    public function show($id)
    {
        $rate = Rate::find($id);
        if (!$rate) {
            return response()->json(['error' => 'Rate not found'], 404);
        }
        return response()->json($rate, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'percentage' => 'required|numeric',
        ]);
        $rate = Rate::create($validated);
        return response()->json($rate, 201);
    }

    public function update(Request $request, $id)
    {
        $rate = Rate::find($id);
        if (!$rate) {
            return response()->json(['error' => 'Rate not found'], 404);
        }
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:100',
            'percentage' => 'sometimes|required|numeric',
        ]);
        $rate->update($validated);
        return response()->json($rate, 200);
    }

    public function destroy($id)
    {
        $rate = Rate::find($id);
        if (!$rate) {
            return response()->json(['error' => 'Rate not found'], 404);
        }
        $rate->delete();
        return response()->json(['message' => 'Rate deleted'], 200);
    }
}
