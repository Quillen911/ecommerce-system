<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\SellerRedirect::class,
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'dev' => [
            \App\Http\Middleware\DevelopmentOnly::class,
        ],
    ];

    protected $routeMiddleware = [
        'DevelopmentOnly' => \App\Http\Middleware\DevelopmentOnly::class, 
        'register.limit' => \App\Http\Middleware\RegisterRateLimit::class,
        'login.limit' => \App\Http\Middleware\LoginRateLimit::class,
    ];

    protected $commands = [
        \App\Console\Commands\ReindexProducts::class,
    ];
}
