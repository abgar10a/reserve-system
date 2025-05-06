<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CurrencySeeder::class,
            RestaurantSeeder::class,
            HallSeeder::class,
            TableSeeder::class,
            OrderSeeder::class,
            PriceSeeder::class,
        ]);
    }
}
