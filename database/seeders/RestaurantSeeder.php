<?php

namespace Database\Seeders;

use App\Repositories\Interfaces\IRestaurantRepository;
use Illuminate\Database\Seeder;

class RestaurantSeeder extends Seeder
{
    public function __construct(private readonly IRestaurantRepository $restaurantRepository)
    {

    }

    public function run(): void
    {

        if (!$this->restaurantRepository->find()) {
            $this->restaurantRepository->create([
                'name' => 'AmBrain',
                'address' => '123 Main St',
                'phone' => '123-456-7890',
                'email' => 'janjan@dd.com',
            ]);
        }
    }
}
