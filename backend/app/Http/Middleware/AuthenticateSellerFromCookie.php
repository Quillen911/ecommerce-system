<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticateSellerFromCookie
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $cookie = $request->cookie('seller_token');
        if ($cookie){
            $cookie = urldecode($cookie);

            if (strpos($cookie, '|') !== false) {
                [$id, $plainToken] = explode('|', $cookie, 2);
                $accessToken = PersonalAccessToken::find($id);
            }

            if ($accessToken && hash_equals($accessToken->token, hash('sha256', $plainToken))) {
                $seller = $accessToken->tokenable;
                
                $request->setUserResolver(fn () => $seller);
                Auth::guard('seller')->setUser($seller);
            }
            

        }
        return $next($request);
    }
}
