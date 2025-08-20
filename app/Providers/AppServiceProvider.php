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
        // Base Repository
        $this->app->bind(
            \App\Repositories\Contracts\BaseRepositoryInterface::class,
            \App\Repositories\Eloquent\BaseRepository::class
        );

        // Product Repository
        $this->app->bind(
            \App\Repositories\Contracts\Product\ProductRepositoryInterface::class,
            \App\Repositories\Eloquent\Product\ProductRepository::class
        );

        // Category Repository
        $this->app->bind(
            \App\Repositories\Contracts\Category\CategoryRepositoryInterface::class,
            \App\Repositories\Eloquent\Category\CategoryRepository::class
        );

        // Store Repository
        $this->app->bind(
            \App\Repositories\Contracts\Store\StoreRepositoryInterface::class,
            \App\Repositories\Eloquent\Store\StoreRepository::class
        );

        // Order Repository
        $this->app->bind(
            \App\Repositories\Contracts\Order\OrderRepositoryInterface::class,
            \App\Repositories\Eloquent\Order\OrderRepository::class
        );

        // Order Item Repository
        $this->app->bind(
            \App\Repositories\Contracts\OrderItem\OrderItemRepositoryInterface::class,
            \App\Repositories\Eloquent\OrderItem\OrderItemRepository::class
        );

        // Campaign Repository
        $this->app->bind(
            \App\Repositories\Contracts\Campaign\CampaignRepositoryInterface::class,
            \App\Repositories\Eloquent\Campaign\CampaignRepository::class
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
