<?php

namespace App\Http\Controllers\Admin;

use App\Actions\ResponseAction;
use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Services\RestaurantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ARestaurantController extends Controller
{

    public function __construct(private readonly RestaurantService $restaurantService)
    {
    }

    public function store(Request $request): ?JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
            ]);

            $restaurant = $this->restaurantService->createRestaurant($validated);

            if (isset($restaurant['error'])) {
                return ResponseAction::error($restaurant['error']);
            }

            return ResponseAction::successData($restaurant['message'], $restaurant);
        } catch (\Throwable $th) {
            return ResponseAction::error($th->getMessage(), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request): ?JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'nullable|string|max:255',
                'address' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:20',
            ]);

            $restaurant = $this->restaurantService->updateRestaurant($validated);

            if (isset($restaurant['error'])) {
                return ResponseAction::error($restaurant['error']);
            }

            return ResponseAction::successData($restaurant['message'], $restaurant);
        } catch (\Throwable $th) {
            return ResponseAction::error($th->getMessage(), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
