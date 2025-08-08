<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\MainController;
use App\Http\Controllers\Web\BagController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\Web\MyOrdersController;

use App\Http\Controllers\Web\Admin\CampaignController;
use App\Http\Controllers\Web\Admin\ProductController;
use App\Http\Controllers\Web\Admin\AdminController;
use App\Http\Controllers\Web\Payments\IyzicoController;
 
Route::get('/login', [AuthController::class, 'login'])->name('login');                                                           
Route::post('/postlogin', [AuthController::class,'postlogin'])->name('postlogin');



Route::middleware(['auth'])->group(function(){
    Route::get('/main', [MainController::class, 'main'])->name('main');
    Route::get('/search', [MainController::class, 'search'])->name('search');
    Route::get('/filter', [MainController::class, 'filter'])->name('filter');
    Route::get('/sorting', [MainController::class, 'sorting'])->name('sorting');
    Route::get('/search/autocomplete', [MainController::class, 'autocomplete'])->name('search/autocomplete');

    Route::prefix('bag')->group(function(){
        Route::get('/', [BagController::class, 'bag'])->name('bag');
        Route::post('/add', [BagController::class, 'add'])->name('add');
        Route::delete('/{id}', [BagController::class, 'delete'])->name('bag.delete');
        
    });

    Route::prefix('order')->group(function(){
        Route::get('/', [OrderController::class, 'order'])->name('order');
        Route::post('/done', [OrderController::class, 'done'])->name('done');
    });

    Route::prefix('myorders')->group(function(){
        Route::get('/', [MyOrdersController::class, 'myorders'])->name('myorders');
        Route::delete('/{id}', [MyOrdersController::class, 'delete'])->name('myorders.delete');
    });

    Route::get('/createOrderJob',[OrderController::class, 'CreateOrderJob'])->name('createOrderJob');

    Route::post('/logout',[AuthController::class, 'logout'])->name('logout');

    Route::prefix('payments')->group(function(){
        Route::post('/storeCreditCard', [IyzicoController::class, 'storeCreditCard'])->name('payments.storeCreditCard');
    });
});

//Admin
Route::prefix('admin')->group(function(){
    Route::get('/',[AdminController::class, 'admin'])->name('admin');    

    //Campaign
    Route::prefix('campaign')->group(function(){
        Route::get('/',[CampaignController::class, 'campaign'])->name('admin.campaign'); 
        Route::get('/storeCampaign',[CampaignController::class, 'storeCampaign'])->name('admin.storeCampaign'); 
        Route::post('/createCampaign',[CampaignController::class, 'createCampaign'])->name('admin.createCampaign');
        Route::get('/editCampaign/{id}',[CampaignController::class, 'editCampaign'])->name('admin.editCampaign');
        Route::post('/updateCampaign/{id}',[CampaignController::class, 'updateCampaign'])->name('admin.updateCampaign'); 
        Route::delete('/deleteCampaign/{id}',[CampaignController::class, 'deleteCampaign'])->name('admin.deleteCampaign');
    });

    //Product
    Route::prefix('product')->group(function(){
        Route::get('/',[ProductController::class, 'product'])->name('admin.product'); 
        Route::get('/storeProduct',[ProductController::class, 'storeProduct'])->name('admin.storeProduct'); 
        Route::post('/createProduct',[ProductController::class, 'createProduct'])->name('admin.createProduct'); 
        Route::get('/editProduct/{id}',[ProductController::class, 'editProduct'])->name('admin.editProduct');
        Route::post('/updateProduct/{id}',[ProductController::class, 'updateProduct'])->name('admin.updateProduct');
        Route::delete('/deleteProduct/{id}',[ProductController::class, 'deleteProduct'])->name('admin.deleteProduct');
        Route::get('/searchProduct',[ProductController::class, 'searchProduct'])->name('admin.searchProduct');
    });

});

