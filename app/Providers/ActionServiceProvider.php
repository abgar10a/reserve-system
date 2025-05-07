<?php

namespace App\Providers;

use App\Services\AuthService;
use App\Services\RestaurantService;
use Illuminate\Support\ServiceProvider;

class ActionServiceProvider extends ServiceProvider
{
    public $singletons = [
        AuthService::class => AuthService::class,
        RestaurantService::class => RestaurantService::class,
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        //
    }
}
