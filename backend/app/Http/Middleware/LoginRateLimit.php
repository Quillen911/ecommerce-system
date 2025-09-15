<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class LoginRateLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1 dakikada maksimum 5 giriş denemesi
        if (RateLimiter::tooManyAttempts('login:'.$request->ip(), 5)) {
            $seconds = RateLimiter::availableIn('login:'.$request->ip());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => "Çok fazla giriş denemesi yaptınız. {$seconds} saniye sonra tekrar deneyin."
                ], 429);
            }
            
            return back()->withErrors([
                'error' => "Çok fazla giriş denemesi yaptınız. {$seconds} saniye sonra tekrar deneyin."
            ]);
        }
        
        RateLimiter::hit('login:'.$request->ip(), 60); // 1 dakika
        
        return $next($request);
    }
}
