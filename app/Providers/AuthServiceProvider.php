<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\UserAddress;
use App\Policies\UserAddressPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        UserAddress::class => UserAddressPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}