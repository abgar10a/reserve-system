<?php

namespace App\Services;

use App\Actions\ResponseAction;
use App\Enums\OrderStatus;
use App\Repositories\Interfaces\IHallRepository;
use App\Repositories\Interfaces\IOrderRepository;
use App\Repositories\Interfaces\IRestaurantRepository;

readonly class RestaurantService
{
    public function __construct(
        private IRestaurantRepository $restaurantRepository,
        private IHallRepository       $hallRepository,
        private IOrderRepository      $orderRepository,
        private HallService           $hallService)
    {

    }

    public function getRestaurantMeta(): array
    {
        $restaurant = $this->restaurantRepository->find();

        return ResponseAction::build('Restaurant info', [
            'restaurant' => $restaurant,
        ]);
    }

    public function getRestaurantInfo(): array
    {
        $restaurantInfo = $this->restaurantRepository->find();

        $hallsInfo = $this->hallService->getHallsInfo();

        return ResponseAction::build('Restaurant info', [
            'restaurant' => $restaurantInfo,
            'halls' => $hallsInfo,
        ]);
    }
}
