<?php

namespace Database\Seeders;

use App\Repositories\Interfaces\ICurrencyRepository;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    public function __construct(private readonly ICurrencyRepository $currencyRepository)
    {

    }

    public function run(): void
    {
        $currencies = [
            ['code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$'],
            ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€'],
            ['code' => 'GBP', 'name' => 'British Pound', 'symbol' => '£'],
            ['code' => 'JPY', 'name' => 'Japanese Yen', 'symbol' => '¥'],
            ['code' => 'AMD', 'name' => 'Armenian Dram', 'symbol' => '֏'],
        ];

        foreach ($currencies as $currency) {
            $this->currencyRepository->create($currency);
        }
    }
}
