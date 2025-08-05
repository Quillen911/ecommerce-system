<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BagController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\MainController;
use App\Http\Controllers\Api\MyOrdersController;
use App\Http\Controllers\Api\Admin\CampaignController;
use App\Http\Controllers\Api\Admin\ProductController;

Route::post('/login',[AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function(){

    Route::apiResource('bags', BagController::class)->only(['index','store','show','destroy']);
    Route::apiResource('orders', OrderController::class)->only(['index','store','show']);
    Route::apiResource('main', MainController::class)->only(['index','show']);
    Route::apiResource('myorders', MyOrdersController::class)->only(['index','show','destroy']);
    Route::apiResource('admin/campaign', CampaignController::class)->only(['index','store','show','update','destroy']);
    Route::apiResource('admin/product', ProductController::class)->only(['index','store','show','update','destroy']);
    Route::post('admin/product/bulk', [ProductController::class, 'bulkStore']);
    
    Route::get('/search', [MainController::class, 'search']);
    Route::get('/filter', [MainController::class, 'filter']);
    Route::get('/sorting', [MainController::class, 'sorting']);
    Route::get('/search/autocomplete', [MainController::class, 'autocomplete']);

    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
