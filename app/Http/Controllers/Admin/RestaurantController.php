<?php

namespace App\Http\Controllers\Admin;

use App\Actions\ResponseAction;
use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Services\RestaurantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class RestaurantController extends Controller
{

    public function __construct(private readonly RestaurantService $restaurantService)
    {
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        $restaurant = Restaurant::create($validated);

        return response()->json([
            'data' => $restaurant
        ], 201);
    }

    public function update(Request $request, string $id)
    {
        $restaurant = Restaurant::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'address' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
        ]);

        $restaurant->update($validated);

        return response()->json([
            'data' => $restaurant
        ]);
    }
}
