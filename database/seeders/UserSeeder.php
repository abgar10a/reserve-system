<?php

namespace Database\Seeders;

use App\Repositories\Interfaces\IUserRepository;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function __construct(private readonly IUserRepository $userRepository)
    {
    }

    public function run(): void
    {
        $faker = Factory::create();

        foreach (range(1, 50) as $index) {
            $this->userRepository->create([
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail,
                'email_verified_at' => now(),
                'password' => '00000000',
                'remember_token' => $faker->unique()->sha256,
            ]);
        }
    }
}
