<?php

namespace App\Providers;

use App\Services\AuthService;
use App\Services\HallService;
use App\Services\RestaurantService;
use App\Services\TableService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class ActionServiceProvider extends ServiceProvider
{
    public $singletons = [
        AuthService::class => AuthService::class,
        RestaurantService::class => RestaurantService::class,
        HallService::class => HallService::class,
        TableService::class => TableService::class,
        UserService::class => UserService::class,
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
