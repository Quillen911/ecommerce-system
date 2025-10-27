<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ResetPasswordController;
use App\Http\Controllers\Api\BagController;
use App\Http\Controllers\Api\MainController;
use App\Http\Controllers\Api\Seller\CampaignController;
use App\Http\Controllers\Api\Seller\ProductController;
use App\Http\Controllers\Api\Seller\CategoryController;
use App\Http\Controllers\Api\Payments\CreditCardController;
use App\Http\Controllers\Api\Payments\IyzicoCallbackController;
use App\Http\Controllers\Api\Seller\SellerOrderController;
use App\Http\Controllers\Api\User\AddressesController;

use App\Http\Controllers\Api\Order\OrderController;
use App\Http\Controllers\Api\Order\OrderRefundController;
use App\Http\Controllers\Api\Order\OrderRefundWebhookController;

use App\Http\Controllers\Api\ElasticSearch\CategoryFilterController;
use App\Http\Controllers\Api\ElasticSearch\SearchController;

use App\Http\Controllers\Api\Seller\Image\ProductVariantImageController;
use App\Http\Controllers\Api\Product\ProductVariantController;
use App\Http\Controllers\Api\Seller\VariantController;
use App\Http\Controllers\Api\Seller\VariantSizeController;
use App\Http\Middleware\ApiAuthenticate;
use App\Http\Controllers\Api\Checkout\CheckoutController;
use App\Http\Middleware\LoginRateLimit;
use App\Http\Middleware\RegisterRateLimit;
use App\Http\Middleware\AuthenticateFromCookie;
use App\Http\Middleware\AuthenticateSellerFromCookie;


Route::post('/register', [AuthController::class, 'register'])->middleware(RegisterRateLimit::class);
Route::post('/login', [AuthController::class, 'login'])->middleware(LoginRateLimit::class);
Route::post('/forgot-password', [ResetPasswordController::class, 'forgotPassword']);
Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword']);
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

Route::middleware(AuthenticateFromCookie::class)->middleware('auth:user')->middleware(ApiAuthenticate::class)->group(function(){

    
    Route::post('bags/campaign', [BagController::class, 'select']);
    Route::delete('bags/campaign', [BagController::class, 'unSelectCampaign']);
    Route::apiResource('bags', BagController::class)->only(['index','store','show', 'update', 'destroy']);

    Route::prefix('checkout')->group(function () {
        Route::get('session/{session_id}', [CheckoutController::class, 'getSession']);
        Route::post('session', [CheckoutController::class, 'createSession']);
        Route::post('shipping', [CheckoutController::class, 'updateShipping']);
        Route::post('payment-intent', [CheckoutController::class, 'createPaymentIntent']);
    });

    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::get('/{order}', [OrderController::class, 'show']);
        Route::post('/{order}/refund', [OrderController::class, 'refundItems']); 

    }); 

    Route::prefix('orders/{order}/refunds')->group(function () {
        Route::get('/', [OrderRefundController::class, 'index']);    
        Route::post('/', [OrderRefundController::class, 'store']);     
    });

    Route::prefix('refunds/webhooks')->group(function () {
        Route::post('/shipment', [OrderRefundWebhookController::class, 'handleShipmentStatus'])->middleware('verify.refund-webhook:shipment');
        Route::post('/payment', [OrderRefundWebhookController::class, 'handlePaymentStatus'])->middleware('verify.refund-webhook:payment');
    });
    
    Route::apiResource('creditcard', CreditCardController::class);
     
    Route::get('/me', [AuthController::class, 'me']);

    Route::prefix('account')->group(function(){
        Route::apiResource('addresses', AddressesController::class);
    
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::put('/profile', [AuthController::class, 'updateProfile']);
    });
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware(AuthenticateSellerFromCookie::class)->middleware('auth:seller')->group(function(){

    Route::post('/seller-logout', [AuthController::class, 'sellerLogout']);
    Route::get('/my-seller', [AuthController::class, 'mySeller']);
    
    Route::prefix('seller')->group(function(){
        Route::post('product/bulk', [ProductController::class, 'bulkStore']);
        Route::get('product/search', [ProductController::class, 'searchProduct']);

        Route::apiResource('campaign', CampaignController::class);
        
        Route::apiResource('product', ProductController::class);
        Route::get('/product/{product:slug}', [ProductController::class, 'productDetail']);
        
        Route::prefix('product/{product}')->group(function () {  
            Route::get('variants', [VariantController::class, 'index']);          
            Route::post('variants', [VariantController::class, 'store']);  
                    
            Route::prefix('variants/{variant}')->group(function () {
                Route::get('/', [VariantController::class, 'show']);
                Route::put('/', [VariantController::class, 'update']);
                Route::delete('/', [VariantController::class, 'destroy']);

                Route::post('sizes', [VariantSizeController::class, 'store']);
                Route::put('sizes/{size}', [VariantSizeController::class, 'update']);
                Route::delete('sizes/{size}', [VariantSizeController::class, 'destroy']);

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

Route::post('proxy/iyzico-callback', IyzicoCallbackController::class);
Route::post('checkout/confirm', [CheckoutController::class, 'confirmOrder'])
    ->withoutMiddleware('auth:sanctum')
    ->name('checkout.confirm');