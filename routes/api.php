<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BagController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\MainController;
use App\Http\Controllers\Api\MyOrdersController;
use App\Http\Controllers\Api\Seller\CampaignController;
use App\Http\Controllers\Api\Seller\ProductController;
use App\Http\Controllers\Api\Payments\CreditCardController;
use App\Http\Controllers\Api\Seller\SellerOrderController;

Route::post('/login',[AuthController::class, 'login']);
Route::post('/seller/login',[AuthController::class, 'sellerLogin']);


Route::middleware('auth:sanctum')->group(function(){

    Route::apiResource('bags', BagController::class)->only(['index','store','show','destroy']);
    Route::apiResource('orders', OrderController::class)->only(['index','store','show']);
    Route::apiResource('main', MainController::class)->only(['index','show']);
    Route::apiResource('myorders', MyOrdersController::class)->only(['index','show','destroy']);
   
    Route::apiResource('creditcard', CreditCardController::class);
    
    Route::post('/myorders/{id}/refund', [MyOrdersController::class, 'refundItems']);

    Route::get('/search', [MainController::class, 'search']);
    Route::get('/filter', [MainController::class, 'filter']);
    Route::get('/sorting', [MainController::class, 'sorting']);
    Route::get('/search/autocomplete', [MainController::class, 'autocomplete']);

    Route::get('/my-seller', [AuthController::class, 'mySeller']);
    Route::post('/seller-logout', [AuthController::class, 'sellerLogout']);

    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:sanctum')->group(function(){

    Route::post('/seller-logout', [AuthController::class, 'sellerLogout']);
    Route::get('/my-seller', [AuthController::class, 'mySeller']);
    
    Route::prefix('seller')->group(function(){
        Route::post('product/bulk', [ProductController::class, 'bulkStore']);
        Route::get('product/search', [ProductController::class, 'searchProduct']);

        Route::apiResource('campaign', CampaignController::class);
        Route::apiResource('product', ProductController::class);

        Route::apiResource('order', SellerOrderController::class)->only(['index','show']);
        Route::post('order/{id}/confirm', [SellerOrderController::class, 'confirmOrderItem']);
        Route::post('order/{id}/cancel', [SellerOrderController::class, 'cancelOrderItem']);
    });
    
});
