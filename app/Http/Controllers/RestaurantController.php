<?php

namespace App\Http\Controllers;

use App\Actions\ResponseAction;
use App\Services\RestaurantService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class RestaurantController extends Controller
{

    public function __construct(private readonly RestaurantService $restaurantService)
    {
    }

    public function show(): ?JsonResponse
    {
        try {
            $restaurantResponse = $this->restaurantService->getRestaurantMeta();

            if (isset($restaurantResponse['error'])) {
                return ResponseAction::error($restaurantResponse['error']);
            }

            return ResponseAction::successData($restaurantResponse['message'], $restaurantResponse);
        } catch (\Throwable $th) {
            return ResponseAction::error($th->getMessage(), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function showAll(): ?JsonResponse
    {
        try {
            $restaurantResponse = $this->restaurantService->getRestaurantInfo();

            if (isset($restaurantResponse['error'])) {
                return ResponseAction::error($restaurantResponse['error']);
            }

            return ResponseAction::successData($restaurantResponse['message'], $restaurantResponse);
        } catch (\Throwable $th) {
            return ResponseAction::error($th->getMessage(), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
