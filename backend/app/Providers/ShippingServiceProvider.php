<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Shipping\Contracts\ShippingServiceInterface;
use App\Services\Shipping\Services\MNGService;

class ShippingServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(ShippingServiceInterface::class, function ($app) {
            return new MNGService();
        });
    }
}
