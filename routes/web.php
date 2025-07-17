<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\MainController;
use App\Http\Controllers\Web\BagController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\Web\MyOrdersController;
 
Route::get('/login', [AuthController::class, 'login'])->name('login');                                                           
Route::post('/postlogin', [AuthController::class,'postlogin'])->name('postlogin');



Route::middleware(['auth'])->group(function(){
    Route::get('/main', [MainController::class, 'main'])->name('main');
    
    Route::prefix('bag')->group(function(){
        Route::get('/', [BagController::class, 'bag'])->name('bag');
        Route::post('/add', [BagController::class, 'add'])->name('add');
        Route::delete('/{id}', [BagController::class, 'delete'])->name('delete');
        
    });
    Route::get('/order', [OrderController::class, 'order'])->name('order');
    Route::post('/ordergo', [OrderController::class, 'ordergo'])->name('ordergo');
    Route::get('/myorders', [MyOrdersController::class, 'myorders'])->name('myorders');
    Route::get('/createOrderJob',[OrderController::class, 'CreateOrderJob'])->name('createOrderJob');
    
    Route::post('/logout',[AuthController::class, 'logout'])->name('logout');
});    

