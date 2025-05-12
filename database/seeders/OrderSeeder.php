<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Repositories\Interfaces\IHallRepository;
use App\Repositories\Interfaces\IOrderRepository;
use App\Repositories\Interfaces\ITableRepository;
use App\Repositories\Interfaces\IUserRepository;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use App\Models\Table;
use App\Models\Hall;

class OrderSeeder extends Seeder
{
    public function __construct(
        private readonly IOrderRepository $orderRepository,
        private readonly IUserRepository  $userRepository,
        private readonly ITableRepository $tableRepository,
        private readonly IHallRepository  $hallRepository)
    {
    }

    public function run(): void
    {
        $faker = Factory::create();

        $users = $this->userRepository->all();
        $tables = $this->tableRepository->all();
        $halls = $this->hallRepository->all();

        foreach (range(1, 100) as $index) {
            $user = $users->random();

            $entityType = $faker->randomElement([Table::class, Hall::class]);

            if ($entityType === Table::class) {
                $entity = $tables->random();
            } else {
                $entity = $halls->random();
            }

            $start = Carbon::now()->subMonths(3)->addDays(random_int(0, 90))->addHours(random_int(0, 24));
            $end = $start->copy()->addHours(random_int(1, 7));

            $this->orderRepository->create([
                'user' => $user->id,
                'start' => $start,
                'end' => $end,
                'status' => $faker->randomElement(OrderStatus::all()),
                'entity_id' => $entity->id,
                'entity_type' => $entityType,
            ]);
        }
    }
}
