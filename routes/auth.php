<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


Route::middleware('guest')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->block(2, 2);

    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/{provider}', [AuthController::class, 'redirectToProvider']);

    Route::post('/{provider}/callback', [AuthController::class, 'handleProviderCallback']);
});


Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh-token', [AuthController::class, 'refreshToken']);
});
