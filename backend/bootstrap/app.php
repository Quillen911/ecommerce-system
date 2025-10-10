<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'seller.auth' => \App\Http\Middleware\SellerRedirect::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        // RuntimeException veya genel exception'lar için JSON çıktı
        $exceptions->render(function (Throwable $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                $status = 500;

                if ($e instanceof \RuntimeException) {
                    $status = 400;
                }

                return response()->json([
                    'message' => $e->getMessage(),
                ], $status);
            }

            return null; // web istekleri default davranışta kalsın
        });

        // MethodNotAllowed gibi özel exception'ların override'ı (senin mevcut kodun)
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException $e, $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return null; // varsayılan 405 handling
            }
        });
    })
    ->create();
