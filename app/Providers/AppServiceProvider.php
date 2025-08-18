<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Services\Order\Contracts\OrderServiceInterface::class,
            \App\Services\Order\Services\OrderService::class
        );
        
        $this->app->bind(
            \App\Services\Order\Contracts\CalculationInterface::class,
            \App\Services\Order\Services\CalculationService::class
        );
        
        $this->app->bind(
            \App\Services\Order\Contracts\PaymentInterface::class,
            \App\Services\Order\Services\PaymentService::class
        );
        
        $this->app->bind(
            \App\Services\Order\Contracts\InventoryInterface::class,
            \App\Services\Order\Services\InventoryService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
