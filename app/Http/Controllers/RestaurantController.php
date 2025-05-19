<?php

namespace App\Http\Controllers;

use App\Actions\ResponseAction;
use App\Services\RestaurantService;
use Illuminate\Http\JsonResponse;

class RestaurantController extends Controller
{

    public function __construct(private readonly RestaurantService $restaurantService)
    {
    }

    public function show(): ?JsonResponse
    {
//        dd($this->restaurantService->getRestaurantMeta());

        return ResponseAction::handleResponse($this->restaurantService->getRestaurantMeta());
    }

    public function showAll(): ?JsonResponse
    {
        return ResponseAction::handleResponse($this->restaurantService->getRestaurantInfo());
    }
}
