<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BagController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\MainController;
use App\Http\Controllers\Api\MyOrdersController;
use App\Http\Controllers\Api\Seller\CampaignController;
use App\Http\Controllers\Api\Seller\ProductController;
use App\Http\Controllers\Api\Seller\CategoryController;
use App\Http\Controllers\Api\Payments\CreditCardController;
use App\Http\Controllers\Api\Seller\SellerOrderController;
use App\Http\Controllers\Api\User\AddressesController;

use App\Http\Controllers\Api\ElasticSearch\CategoryFilterController;
use App\Http\Controllers\Api\ElasticSearch\SearchController;

use App\Http\Controllers\Api\Seller\Image\ProductImageController;
use App\Http\Controllers\Api\Seller\Image\ProductVariantImageController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/seller/login', [AuthController::class, 'sellerLogin']);

Route::prefix('main')->group(function(){
    Route::get('/', [MainController::class, 'main']);
    Route::get('/product/{product:slug}', [MainController::class, 'productDetail']);
});

Route::prefix('category')->group(function(){
    Route::get('/{category_slug}', [CategoryFilterController::class, 'categoryFilter']);
});

Route::get('/search', [SearchController::class, 'search']);
Route::get('/filter', [MainController::class, 'filter']);
Route::get('/sorting', [MainController::class, 'sorting']);
Route::get('/autocomplete', [MainController::class, 'autocomplete']);

Route::middleware('auth:user')->group(function(){

    Route::apiResource('bags', BagController::class)->only(['index','store','show', 'update', 'destroy']);
    Route::apiResource('orders', OrderController::class)->only(['index','store','show']);
    Route::apiResource('myorders', MyOrdersController::class)->only(['index','show','destroy']);
   
    Route::apiResource('creditcard', CreditCardController::class);
    
    Route::post('/myorders/{id}/refund', [MyOrdersController::class, 'refundItems']);


    Route::get('/me', [AuthController::class, 'me']);

    Route::prefix('account')->group(function(){
        Route::apiResource('addresses', AddressesController::class);
    
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::put('/profile', [AuthController::class, 'updateProfile']);
    });
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:seller')->group(function(){

    Route::post('/seller-logout', [AuthController::class, 'sellerLogout']);
    Route::get('/my-seller', [AuthController::class, 'mySeller']);
    
    Route::prefix('seller')->group(function(){
        Route::post('product/bulk', [ProductController::class, 'bulkStore']);
        Route::get('product/search', [ProductController::class, 'searchProduct']);

        Route::apiResource('campaign', CampaignController::class);
        Route::apiResource('product', ProductController::class);
        //Route::put('product/{product:slug}', ProductController::class);

        Route::prefix('product/{product}')->group(function () {
            Route::post('images', [ProductImageController::class, 'store']);
            Route::delete('images/{image}', [ProductImageController::class, 'destroy']);
            Route::put('images/reorder', [ProductImageController::class, 'reorder']);
            
            Route::prefix('variants/{variantId}')->group(function () {
                Route::post('images', [ProductVariantImageController::class, 'store']);
                Route::delete('images/{image}', [ProductVariantImageController::class, 'destroy']);
                Route::put('images/reorder', [ProductVariantImageController::class, 'reorder']);
            });
        });

        Route::get('/categories/{id}/children', [CategoryController::class, 'children']);


        Route::apiResource('order', SellerOrderController::class)->only(['index','show']);
        Route::post('orderitem/{id}/confirm', [SellerOrderController::class, 'confirmOrderItem']);
        Route::post('orderitem/{id}/refund', [SellerOrderController::class, 'refundOrderItem']);

    });
    
});
