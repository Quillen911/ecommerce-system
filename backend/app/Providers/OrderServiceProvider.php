<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Order\Contracts\OrderInterface;
use App\Services\Order\Contracts\OrderRefundInterface;
use App\Services\Order\Contracts\OrderCheckInterface;
use App\Services\Order\Contracts\OrderCalculationInterface;
use App\Services\Order\Contracts\OrderUpdateInterface;
use App\Services\Order\Services\OrderService;
use App\Services\Order\Services\OrderRefundService;
use App\Services\Order\Services\OrderCheckService;
use App\Services\Order\Services\OrderCalculationService;
use App\Services\Order\Services\OrderUpdateService;

class OrderServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(OrderInterface::class, OrderService::class);
        $this->app->bind(OrderRefundInterface::class, OrderRefundService::class);
        $this->app->bind(OrderCheckInterface::class, OrderCheckService::class);
        $this->app->bind(OrderCalculationInterface::class, OrderCalculationService::class);
        $this->app->bind(OrderUpdateInterface::class, OrderUpdateService::class);
    }

    public function boot()
    {
        //
    }
}