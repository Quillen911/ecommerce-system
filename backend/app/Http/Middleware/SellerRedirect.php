<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\Contracts\AuthenticationRepositoryInterface;

class SellerRedirect
{
    protected $authenticationRepository;

    public function __construct(AuthenticationRepositoryInterface $authenticationRepository)
    {
        $this->authenticationRepository = $authenticationRepository;
    }
    
    public function handle(Request $request, Closure $next)
    {
        
        if (request()->is('seller/*') || request()->is('seller')) {
            if (!request()->is('seller/login')) {
                if (!$this->authenticationRepository->isSellerLoggedIn()) {
                    return redirect()->route('seller.login');
                }
            }
        }
        
        return $next($request);
    }
}
