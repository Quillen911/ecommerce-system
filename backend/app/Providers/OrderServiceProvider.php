<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Order\Contracts\OrderInterface;
use App\Services\Order\Contracts\Refund\OrderRefundInterface;
use App\Services\Order\Contracts\Refund\OrderCheckInterface;
use App\Services\Order\Contracts\Refund\OrderCalculationInterface;
use App\Services\Order\Contracts\Refund\OrderUpdateInterface;
use App\Services\Order\Services\OrderService;
use App\Services\Order\Services\Refund\OrderRefundService;
use App\Services\Order\Services\Refund\OrderCheckService;
use App\Services\Order\Services\Refund\OrderCalculationService;
use App\Services\Order\Services\Refund\OrderUpdateService;

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