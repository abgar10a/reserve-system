<?php

namespace App\Services;

use App\Actions\ResponseAction;
use App\Repositories\Interfaces\IRestaurantRepository;

readonly class RestaurantService
{
    public function __construct(
        private IRestaurantRepository $restaurantRepository,
        private HallService           $hallService)
    {

    }

    public function getRestaurantMeta(): array
    {
        $restaurant = $this->restaurantRepository->find();

        return ResponseAction::build('Restaurant meta', [
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

    public function createRestaurant($restaurantData): array
    {
        $metaExists = $this->restaurantRepository->find()->exists();

        if ($metaExists) {
            return ResponseAction::build(error: 'Restaurant meta already exists');
        }

        $restaurant = $this->restaurantRepository->create($restaurantData);

        return ResponseAction::build('Restaurant meta created', [
            'restaurant' => $restaurant,
        ]);
    }

    public function updateRestaurant($restaurantData): array
    {
        if (empty($restaurantData)) {
            return ResponseAction::build(error: 'Empty data');
        }

        $restaurant = $this->restaurantRepository->updateMeta($restaurantData);

        return ResponseAction::build('Restaurant meta updated', [
            'restaurant' => $restaurant,
        ]);
    }
}
