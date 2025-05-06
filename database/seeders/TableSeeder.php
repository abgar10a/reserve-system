<?php

namespace Database\Seeders;

use App\Repositories\Interfaces\IHallRepository;
use App\Repositories\Interfaces\ITableRepository;
use Faker\Factory;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    public function __construct(
        private readonly ITableRepository $tableRepository,
        private readonly IHallRepository  $hallRepository)
    {

    }

    public function run(): void
    {
        $faker = Factory::create();

        $halls = $this->hallRepository->all();

        foreach ($halls as $hall) {
            for ($i = 0; $i < 5; $i++) {
                $this->tableRepository->create([
                    'name' => 'Table ' . ($i + 1),
                    'hall' => $hall->id,
                    'seats' => $faker->randomElement([10, 20, 30]),
                ]);
            }
        }
    }
}
