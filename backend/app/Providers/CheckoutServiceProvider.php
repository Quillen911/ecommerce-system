<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\Contracts\Payment\PaymentMethodRepositoryInterface;
use App\Repositories\Eloquent\Payment\PaymentMethodRepository;

use App\Repositories\Contracts\Payment\PaymentProviderRepositoryInterface;
use App\Repositories\Eloquent\Payment\PaymentProviderRepository;

use App\Repositories\Contracts\Payment\PaymentRepositoryInterface;
use App\Repositories\Eloquent\Payment\PaymentRepository;


class CheckoutServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            PaymentMethodRepositoryInterface::class,
            PaymentMethodRepository::class
        );

        $this->app->bind(
            PaymentProviderRepositoryInterface::class,
            PaymentProviderRepository::class
        );

        $this->app->bind(
            PaymentRepositoryInterface::class,
            PaymentRepository::class
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

