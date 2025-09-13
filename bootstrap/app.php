<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'seller.auth' => \App\Http\Middleware\SellerRedirect::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException $e, $request) {
            
            // API ve JSON istekleri -> 405 bırak
            if ($request->is('api/*') || $request->expectsJson()) {
                return null; // varsayılan 405 handling
            }

            // Web isteklerinde GET için akıllı redirect
            if ($request->isMethod('GET')) {
                // Sepet işlemleri -> sepet sayfasına
                if ($request->is('bag/*')) {
                    return redirect()->route('bag');
                }
                
                // Sipariş işlemleri -> sipariş sayfasına
                if ($request->is('order/*')) {
                    return redirect()->route('order');
                }
                
                // Siparişlerim işlemleri -> siparişlerim sayfasına
                if ($request->is('myorders/*')) {
                    return redirect()->route('myorders');
                }
                
                // Hesap işlemleri -> ana sayfaya (güvenlik için)
                if ($request->is('account/*')) {
                    return redirect()->route('main');
                }
                
                // Diğer tüm durumlar -> ana sayfaya
                return redirect()->route('main');
            }

            return null; // diğer durumlarda default handling
        });
    })->create();
