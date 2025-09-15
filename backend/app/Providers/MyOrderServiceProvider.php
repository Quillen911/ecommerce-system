<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\MyOrder\Contracts\MyOrderInterface;
use App\Services\MyOrder\Contracts\MyOrderRefundInterface;
use App\Services\MyOrder\Contracts\MyOrderCheckInterface;
use App\Services\MyOrder\Contracts\MyOrderCalculationInterface;
use App\Services\MyOrder\Contracts\MyOrderUpdateInterface;
use App\Services\MyOrder\Services\MyOrderService;
use App\Services\MyOrder\Services\MyOrderRefundService;
use App\Services\MyOrder\Services\MyOrderCheckService;
use App\Services\MyOrder\Services\MyOrderCalculationService;
use App\Services\MyOrder\Services\MyOrderUpdateService;

class MyOrderServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(MyOrderInterface::class, MyOrderService::class);
        $this->app->bind(MyOrderRefundInterface::class, MyOrderRefundService::class);
        $this->app->bind(MyOrderCheckInterface::class, MyOrderCheckService::class);
        $this->app->bind(MyOrderCalculationInterface::class, MyOrderCalculationService::class);
        $this->app->bind(MyOrderUpdateInterface::class, MyOrderUpdateService::class);
    }

    public function boot()
    {
        //
    }
}