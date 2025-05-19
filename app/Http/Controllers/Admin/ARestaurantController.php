<?php

namespace App\Http\Controllers\Admin;

use App\Actions\ResponseAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRestaurantRequest;
use App\Http\Requests\UpdateRestaurantRequest;
use App\Services\RestaurantService;
use Illuminate\Http\JsonResponse;

class ARestaurantController extends Controller
{

    public function __construct(private readonly RestaurantService $restaurantService)
    {
    }

    public function store(StoreRestaurantRequest $request): ?JsonResponse
    {
        $validated = $request->validated();

        return ResponseAction::handleResponse($this->restaurantService->createRestaurant($validated));
    }

    public function update(UpdateRestaurantRequest $request): ?JsonResponse
    {
        $validated = $request->validated();

        return ResponseAction::handleResponse($this->restaurantService->updateRestaurant($validated));
    }
}
