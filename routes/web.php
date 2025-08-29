<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\MainController;
use App\Http\Controllers\Web\BagController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\Web\MyOrdersController;

use App\Http\Controllers\Web\Seller\CampaignController;
use App\Http\Controllers\Web\Seller\ProductController;
use App\Http\Controllers\Web\Payments\CreditCardController;
use App\Http\Controllers\Web\Seller\SellerController;
use App\Http\Controllers\Web\Seller\SellerOrderController;
use App\Http\Controllers\Web\Seller\SellerSettingsController;
use App\Http\Middleware\SellerRedirect;
use App\Http\Controllers\DevelopmentController;
use App\Http\Middleware\DevelopmentOnly;
 
Route::get('/login', [AuthController::class, 'login'])->name('login');                                                           
Route::post('/postlogin', [AuthController::class,'postlogin'])->name('postlogin');



Route::middleware(['auth:user_web'])->group(function(){
    Route::get('/main', [MainController::class, 'main'])->name('main');
    Route::get('/search', [MainController::class, 'search'])->name('search');
    Route::get('/filter', [MainController::class, 'filter'])->name('filter');
    Route::get('/sorting', [MainController::class, 'sorting'])->name('sorting');
    Route::get('/search/autocomplete', [MainController::class, 'autocomplete'])->name('search/autocomplete');

    Route::prefix('bag')->group(function(){
        Route::get('/', [BagController::class, 'bag'])->name('bag');
        Route::post('/add', [BagController::class, 'add'])->name('add');
        Route::post('/update/{id}', [BagController::class, 'update'])->name('bag.update');
        Route::delete('/{id}', [BagController::class, 'delete'])->name('bag.delete');
        
    });

    Route::prefix('order')->group(function(){
        Route::get('/', [OrderController::class, 'order'])->name('order');
        Route::post('/done', [OrderController::class, 'done'])->name('done');
        // Route::post('/payment/callback', [OrderController::class, 'paymentCallback'])->name('payment.callback'); //iyzico payment callback
    });

    Route::prefix('myorders')->group(function(){
        Route::get('/', [MyOrdersController::class, 'myorders'])->name('myorders');
        Route::delete('/{id}', [MyOrdersController::class, 'delete'])->name('myorders.delete');
        Route::post('/{id}/refund', [MyOrdersController::class, 'refundItems'])->name('myorders.refundItems');
    });

    Route::post('/logout',[AuthController::class, 'logout'])->name('logout');

    Route::prefix('payments')->group(function(){
        Route::post('/storeCreditCard', [CreditCardController::class, 'storeCreditCard'])->name('payments.storeCreditCard');
    });
});


//Seller
Route::get('/seller/login',[AuthController::class, 'sellerLogin'])->name('seller.login'); 
Route::post('/seller/postlogin', [AuthController::class,'sellerPostlogin'])->name('seller.postlogin');

Route::middleware(['auth:seller_web',])->middleware(SellerRedirect::class)->group(function(){
    
    Route::prefix('seller')->group(function(){
        Route::get('/',[SellerController::class, 'seller'])->name('seller');  
        Route::post('/logout', [AuthController::class, 'sellerLogout'])->name('seller.logout');

        //Campaign
        Route::prefix('campaign')->group(function(){
            Route::get('/',[CampaignController::class, 'campaign'])->name('seller.campaign'); 
            Route::get('/storeCampaign',[CampaignController::class, 'storeCampaign'])->name('seller.storeCampaign'); 
            Route::post('/createCampaign',[CampaignController::class, 'createCampaign'])->name('seller.createCampaign');
            Route::get('/editCampaign/{id}',[CampaignController::class, 'editCampaign'])->name('seller.editCampaign');
            Route::post('/updateCampaign/{id}',[CampaignController::class, 'updateCampaign'])->name('seller.updateCampaign'); 
            Route::delete('/deleteCampaign/{id}',[CampaignController::class, 'deleteCampaign'])->name('seller.deleteCampaign');
        });

        //Product
        Route::prefix('product')->group(function(){
            Route::get('/',[ProductController::class, 'product'])->name('seller.product'); 
            Route::get('/storeProduct',[ProductController::class, 'storeProduct'])->name('seller.storeProduct'); 
            Route::post('/createProduct',[ProductController::class, 'createProduct'])->name('seller.createProduct'); 
            Route::get('/editProduct/{id}',[ProductController::class, 'editProduct'])->name('seller.editProduct');
            Route::post('/updateProduct/{id}',[ProductController::class, 'updateProduct'])->name('seller.updateProduct');
            Route::delete('/deleteProduct/{id}',[ProductController::class, 'deleteProduct'])->name('seller.deleteProduct');
            Route::get('/searchProduct',[ProductController::class, 'searchProduct'])->name('seller.searchProduct');
        });
        //Order
        Route::prefix('order')->group(function(){
            Route::get('/',[SellerOrderController::class, 'sellerOrders'])->name('seller.order');
            Route::post('orders/{id}/confirm', [SellerOrderController::class, 'confirmOrderItem'])->name('seller.confirmOrderItem');
            Route::post('orders/{id}/refund', [SellerOrderController::class, 'refundOrderItem'])->name('seller.refundOrderItem');
        }); 

        Route::resource('/settings', SellerSettingsController::class);

    });
});

Route::middleware(DevelopmentOnly::class)->prefix('dev')->name('development.')->group(function() {
    Route::get('/fake-sms', [DevelopmentController::class, 'showFakeSms'])->name('fake-sms');
    Route::delete('/fake-sms', [DevelopmentController::class, 'clearFakeSms'])->name('fake-sms.clear');
});