<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class RegisterRateLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1 dakikada maksimum 3 kayıt denemesi
        if (RateLimiter::tooManyAttempts('register:'.$request->ip(), 3)) {
            $seconds = RateLimiter::availableIn('register:'.$request->ip());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => "Çok fazla kayıt denemesi yaptınız. {$seconds} saniye sonra tekrar deneyin."
                ], 429);
            }
            
            return back()->withErrors([
                'error' => "Çok fazla kayıt denemesi yaptınız. {$seconds} saniye sonra tekrar deneyin."
            ]);
        }
        
        RateLimiter::hit('register:'.$request->ip(), 60); // 1 dakika
        
        return $next($request);
    }
}
