<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Order\Contracts\CalculationInterface;
use App\Services\Order\Contracts\PaymentInterface;
use App\Services\Order\Contracts\InventoryInterface;
use App\Services\Order\Services\CalculationService;
use App\Services\Order\Services\PaymentService;
use App\Services\Order\Services\InventoryService;
use App\Services\Order\Contracts\OrderServiceInterface;
use App\Services\Order\Services\OrderService;

class OrderServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(OrderServiceInterface::class, OrderService::class);
        $this->app->bind(CalculationInterface::class, CalculationService::class);
        $this->app->bind(PaymentInterface::class, PaymentService::class);
        $this->app->bind(InventoryInterface::class, InventoryService::class);
        

        $this->app->singleton(OrderService::class);
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/order.php' => config_path('order.php'),
        ], 'order-config');
    }
}