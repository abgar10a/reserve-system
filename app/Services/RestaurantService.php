<?php

namespace App\Services;

use App\Actions\ResponseAction;
use App\Enums\ResponseStatus;
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
        ], ResponseStatus::OK->code());
    }

    public function getRestaurantInfo(): array
    {
        $restaurantInfo = $this->restaurantRepository->find();

        $hallsInfo = $this->hallService->getHallsInfo();

        return ResponseAction::build('Restaurant info', [
            'restaurant' => $restaurantInfo,
            'halls' => $hallsInfo,
        ], ResponseStatus::OK->code());
    }

    public function createRestaurant(array $restaurantData): array
    {
        $metaExists = $this->restaurantRepository->find()->exists();

        if ($metaExists) {
            return ResponseAction::build(status: ResponseStatus::CONFLICT->code(), error: 'Restaurant meta already exists');
        }

        $restaurant = $this->restaurantRepository->create($restaurantData);

        return ResponseAction::build('Restaurant meta created', [
            'restaurant' => $restaurant,
        ], ResponseStatus::CREATED->code());
    }

    public function updateRestaurant(array $restaurantData): array
    {
        $restaurant = $this->restaurantRepository->updateMeta($restaurantData);

        return ResponseAction::build('Restaurant meta updated', [
            'restaurant' => $restaurant,
        ], ResponseStatus::UPDATED->code());
    }
}
