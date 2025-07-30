<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BagController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\MainController;
use App\Http\Controllers\Api\MyOrdersController;
use App\Http\Controllers\Api\ProductController;

Route::post('/login',[AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function(){

    Route::apiResource('bags', BagController::class)->only(['index','store','show','destroy']);
    Route::apiResource('orders', OrderController::class)->only(['index','store','show']);
    Route::apiResource('main', MainController::class)->only(['index','show']);
    Route::apiResource('myorders', MyOrdersController::class)->only(['index','show','destroy']);
    
    Route::get('/search/products', [MainController::class, 'search']);
    Route::get('/search/autocomplete', [MainController::class, 'autocomplete']);

    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
