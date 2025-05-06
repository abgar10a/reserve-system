<?php

namespace Database\Seeders;

use App\Repositories\Interfaces\IHallRepository;
use Illuminate\Database\Seeder;

class HallSeeder extends Seeder
{
    public function __construct(private readonly IHallRepository $hallRepository)
    {

    }

    public function run(): void
    {
        if ($this->hallRepository->all()) {
            $halls = [
                ['name' => 'Hall 1'],
                ['name' => 'Hall 2'],
                ['name' => 'Hall 3'],
            ];

            foreach ($halls as $hall) {
                $this->hallRepository->create($hall);
            }
        }
    }
}
