<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DevelopmentOnly
{
    public function handle(Request $request, Closure $next)
    {
        if (!app()->environment('local', 'development')) {
            abort(404, 'Bu sayfa sadece development ortamında erişilebilir.');
        }
        
        return $next($request);
    }
}
