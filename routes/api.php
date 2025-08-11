<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BagController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\MainController;
use App\Http\Controllers\Api\MyOrdersController;
use App\Http\Controllers\Api\Admin\CampaignController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\Payments\CreditCardController;

Route::post('/login',[AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function(){

    Route::apiResource('bags', BagController::class)->only(['index','store','show','destroy']);
    Route::apiResource('orders', OrderController::class)->only(['index','store','show']);
    Route::apiResource('main', MainController::class)->only(['index','show']);
    Route::apiResource('myorders', MyOrdersController::class)->only(['index','show','destroy']);
   
    Route::apiResource('admin/campaign', CampaignController::class);
    Route::apiResource('admin/product', ProductController::class);
    Route::apiResource('creditcard', CreditCardController::class);
    
    Route::post('/myorders/{id}/refund', [MyOrdersController::class, 'refundItems']);

    Route::post('admin/product/bulk', [ProductController::class, 'bulkStore']);
    Route::get('/admin/product/search', [ProductController::class, 'searchProduct']);

    
    Route::get('/search', [MainController::class, 'search']);
    Route::get('/filter', [MainController::class, 'filter']);
    Route::get('/sorting', [MainController::class, 'sorting']);
    Route::get('/search/autocomplete', [MainController::class, 'autocomplete']);

    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
