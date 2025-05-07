<?php

use App\Enums\UserRole;
use App\Http\Controllers\RestaurantController;
use App\Http\Middleware\UserRoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/restaurant', [RestaurantController::class, 'show']);
Route::get('/restaurant-meta', [RestaurantController::class, 'showAll']);


Route::middleware(UserRoleMiddleware::class . ':' . UserRole::ADMIN->value)->group(function () {
    Route::post('/restaurant', [RestaurantController::class, 'store']);
    Route::patch('/restaurant', [RestaurantController::class, 'update']);
});
