<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\SmsService;
use App\Channels\SmsChannel;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantImage;
use App\Models\VariantAttribute;
use App\Observers\ProductObserver;
use App\Observers\ProductVariantObserver;
use App\Observers\ProductVariantImageObserver;
use App\Observers\VariantAttributeObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Authentication Repository
        $this->app->bind(
            \App\Repositories\Contracts\AuthenticationRepositoryInterface::class,
            \App\Repositories\Eloquent\AuthenticationRepository::class
        );

        // Base Repository
        $this->app->bind(
            \App\Repositories\Contracts\BaseRepositoryInterface::class,
            \App\Repositories\Eloquent\BaseRepository::class
        );

        // Bag Repository
        $this->app->bind(
            \App\Repositories\Contracts\Bag\BagRepositoryInterface::class,
            \App\Repositories\Eloquent\Bag\BagRepository::class
        );

        // Product Repository
        $this->app->bind(
            \App\Repositories\Contracts\Product\ProductRepositoryInterface::class,
            \App\Repositories\Eloquent\Product\ProductRepository::class
        );

        // Product Variant Repository
        $this->app->bind(
            \App\Repositories\Contracts\Product\ProductVariantRepositoryInterface::class,
            \App\Repositories\Eloquent\Product\ProductVariantRepository::class
        );

        // Category Repository
        $this->app->bind(
            \App\Repositories\Contracts\Category\CategoryRepositoryInterface::class,
            \App\Repositories\Eloquent\Category\CategoryRepository::class
        );

        // Attribute Repository
        $this->app->bind(
            \App\Repositories\Contracts\Attribute\AttributeRepositoryInterface::class,
            \App\Repositories\Eloquent\Attribute\AttributeRepository::class
        );

        // Attribute Options Repository
        $this->app->bind(
            \App\Repositories\Contracts\AttributeOptions\AttributeOptionsRepositoryInterface::class,
            \App\Repositories\Eloquent\AttributeOptions\AttributeOptionsRepository::class
        );

        // Product Variant Image Repository
        $this->app->bind(
            \App\Repositories\Contracts\Image\ProductVariantImageRepositoryInterface::class,
            \App\Repositories\Eloquent\Image\ProductVariantImageRepository::class
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

        // Credit Card Repository
        $this->app->bind(
            \App\Repositories\Contracts\CreditCard\CreditCardRepositoryInterface::class,
            \App\Repositories\Eloquent\CreditCard\CreditCardRepository::class
        );

        // User Repository
        $this->app->bind(
            \App\Repositories\Contracts\UserRepositoryInterface::class,
            \App\Repositories\Eloquent\UserRepository::class
        );
        // Addresses Repository
        $this->app->bind(
            \App\Repositories\Contracts\User\AddressesRepositoryInterface::class,
            \App\Repositories\Eloquent\User\AddressesRepository::class
        );

        // Campaign Registry
        $this->app->register(CampaignServiceProvider::class);

        $this->app->singleton(SmsService::class);
    
        // SMS channel'ı kaydet
        $this->app->make('Illuminate\Notifications\ChannelManager')
            ->extend('sms', function ($app) {
                return new SmsChannel($app->make(SmsService::class));
            });
            
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Product::observe(ProductObserver::class);
        ProductVariant::observe(ProductVariantObserver::class);
        ProductVariantImage::observe(ProductVariantImageObserver::class);
        VariantAttribute::observe(VariantAttributeObserver::class);
    }
}
