<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BagController;
use App\Http\Controllers\Api\Order\OrderController;
use App\Http\Controllers\Api\MainController;
use App\Http\Controllers\Api\Seller\CampaignController;
use App\Http\Controllers\Api\Seller\ProductController;
use App\Http\Controllers\Api\Seller\CategoryController;
use App\Http\Controllers\Api\Payments\CreditCardController;
use App\Http\Controllers\Api\Seller\SellerOrderController;
use App\Http\Controllers\Api\User\AddressesController;

use App\Http\Controllers\Api\ElasticSearch\CategoryFilterController;
use App\Http\Controllers\Api\ElasticSearch\SearchController;

use App\Http\Controllers\Api\Seller\Image\ProductVariantImageController;
use App\Http\Controllers\Api\Product\ProductVariantController;

use App\Http\Controllers\Api\Checkout\CheckoutController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/seller/login', [AuthController::class, 'sellerLogin']);

Route::prefix('main')->group(function(){
    Route::get('/', [MainController::class, 'main']);
});

Route::get('/variant/{variant:slug}', [ProductVariantController::class, 'variantDetail']);

Route::prefix('category')->group(function(){
    Route::get('/{category_slug}', [CategoryFilterController::class, 'categoryFilter']);
});

Route::get('/search', [SearchController::class, 'search']);
Route::get('/filter', [MainController::class, 'filter']);
Route::get('/sorting', [MainController::class, 'sorting']);
Route::get('/autocomplete', [MainController::class, 'autocomplete']);
Route::middleware('auth:user')->group(function(){

    Route::apiResource('bags', BagController::class)->only(['index','store','show', 'update', 'destroy']);
    
    Route::prefix('checkout')->group(function () {
        Route::post('session', [CheckoutController::class, 'createSession']);
        Route::get('session', [CheckoutController::class, 'getSession']);
        Route::post('shipping', [CheckoutController::class, 'updateShipping']);
        Route::post('payment-intent', [CheckoutController::class, 'createPaymentIntent']);
    });

    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);   
    
    Route::apiResource('creditcard', CreditCardController::class);
    
   // Route::post('/myorders/{id}/refund', [MyOrdersController::class, 'refundItems']);


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

Route::post('proxy/iyzico-callback', function (Request $request) {
    Log::debug('Iyzico mock callback received', $request->all());

    $client = new \GuzzleHttp\Client();
    $client->post('https://nonseriately-uncoded-elba.ngrok-free.dev/api/checkout/confirm', [
        'form_params' => $request->all(),
        'headers' => [
            'User-Agent' => 'curl/7.88.1',
            'ngrok-skip-browser-warning' => 'true'
        ]
    ]);

    return response()->json(['received' => true]);
});

Route::post('checkout/confirm', [CheckoutController::class, 'confirmOrder'])->withoutMiddleware('auth:sanctum')->name('checkout.confirm');
