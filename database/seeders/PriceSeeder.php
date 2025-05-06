<?php

namespace Database\Seeders;

use App\Models\Hall;
use App\Models\Order;
use App\Models\Table;
use App\Repositories\Interfaces\ICurrencyRepository;
use App\Repositories\Interfaces\IHallRepository;
use App\Repositories\Interfaces\IOrderRepository;
use App\Repositories\Interfaces\IPriceRepository;
use App\Repositories\Interfaces\ITableRepository;
use Faker\Factory;
use Illuminate\Database\Seeder;

class PriceSeeder extends Seeder
{
    public function __construct(
        private readonly IOrderRepository    $orderRepository,
        private readonly ICurrencyRepository $currencyRepository,
        private readonly ITableRepository    $tableRepository,
        private readonly IHallRepository     $hallRepository,
        private readonly IPriceRepository    $priceRepository)
    {
    }

    public function run(): void
    {
        $faker = Factory::create();

        $orders = $this->orderRepository->all();
        $tables = $this->tableRepository->all();
        $halls = $this->hallRepository->all();
        $currencies = $this->currencyRepository->all();

        foreach ($orders as $order) {
            $currency = $currencies->random();
            $amount = $faker->randomFloat(2, 10, 1000);

            $this->priceRepository->create([
                'amount' => $amount,
                'currency' => $currency->id,
                'entity_id' => $order->id,
                'entity_type' => Order::class,
            ]);
        }

        foreach ($tables as $table) {
            $currency = $currencies->random();
            $amount = $faker->randomFloat(2, 10, 1000);

            $this->priceRepository->create([
                'amount' => $amount,
                'currency' => $currency->id,
                'entity_id' => $table->id,
                'entity_type' => Table::class,
            ]);
        }

        foreach ($halls as $hall) {
            $currency = $currencies->random();
            $amount = $faker->randomFloat(2, 10, 1000);

            $this->priceRepository->create([
                'amount' => $amount,
                'currency' => $currency->id,
                'entity_id' => $hall->id,
                'entity_type' => Hall::class,
            ]);
        }
    }
}
