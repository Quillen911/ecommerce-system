<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BagController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\MainController;
use App\Http\Controllers\Api\MyOrdersController;

Route::post('/login',[AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function(){

    Route::apiResource('bags', BagController::class);
    Route::apiResource('orders', OrderController::class);
    Route::apiResource('main', MainController::class);
    Route::apiResource('myorders', MyOrdersController::class);

    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});