<?php

use App\Enums\UserRole;
use App\Http\Controllers\Admin\AHallController;
use App\Http\Controllers\Admin\ARestaurantController;
use App\Http\Controllers\Admin\ATableController;
use App\Http\Controllers\Admin\AUserController;
use App\Http\Controllers\HallController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\ApiGuardAuthCheckMiddleware;
use App\Http\Middleware\UserRoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/restaurant', [RestaurantController::class, 'show']);
Route::get('/restaurant-meta', [RestaurantController::class, 'showAll']);

Route::get('/halls', [HallController::class, 'index']);
Route::get('/halls/{id}', [HallController::class, 'show']);

// auth routes
Route::middleware(ApiGuardAuthCheckMiddleware::class)->group(function () {
    // personal info
    Route::get('/users', [UserController::class, 'show']);
    Route::patch('/users', [UserController::class, 'update']);
    Route::delete('/users', [UserController::class, 'delete']);

    // orders
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::patch('/orders', [OrderController::class, 'update']);
    Route::delete('/orders', [OrderController::class, 'delete']);
});

// admin routes
Route::middleware([ApiGuardAuthCheckMiddleware::class , UserRoleMiddleware::class . ':' . UserRole::ADMIN->value])->group(function () {
    // restaurant
    Route::post('/restaurant-meta', [ARestaurantController::class, 'store']);
    Route::patch('/restaurant-meta', [ARestaurantController::class, 'update']);

    // halls
    Route::post('/halls', [AHallController::class, 'store']);
    Route::patch('/halls/{id}', [AHallController::class, 'update']);
    Route::delete('/halls/{id}', [AHallController::class, 'delete']);

    // tables
    Route::post('/tables', [ATableController::class, 'store']);
    Route::patch('/tables/{id}', [ATableController::class, 'update']);
    Route::delete('/tables/{id}', [ATableController::class, 'delete']);

    // users
    Route::get('/users', [AUserController::class, 'index']);
});
