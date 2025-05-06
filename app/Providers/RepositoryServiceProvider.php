<?php

namespace App\Providers;

use App\Repositories\CurrencyRepository;
use App\Repositories\HallRepository;
use App\Repositories\Interfaces\ICurrencyRepository;
use App\Repositories\Interfaces\IHallRepository;
use App\Repositories\Interfaces\IOrderRepository;
use App\Repositories\Interfaces\IPriceRepository;
use App\Repositories\Interfaces\IRestaurantRepository;
use App\Repositories\Interfaces\ITableRepository;
use App\Repositories\Interfaces\IUserRepository;
use App\Repositories\OrderRepository;
use App\Repositories\PriceRepository;
use App\Repositories\RestaurantRepository;
use App\Repositories\TableRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public $bindings = [
        IUserRepository::class => UserRepository::class,
        ICurrencyRepository::class => CurrencyRepository::class,
        IHallRepository::class => HallRepository::class,
        IOrderRepository::class => OrderRepository::class,
        IPriceRepository::class => PriceRepository::class,
        IRestaurantRepository::class => RestaurantRepository::class,
        ITableRepository::class => TableRepository::class,
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
