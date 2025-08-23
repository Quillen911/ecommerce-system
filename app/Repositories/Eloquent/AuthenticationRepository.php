<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\AuthenticationRepositoryInterface;
use App\Models\User;
use App\Models\Seller;
use Illuminate\Support\Facades\Auth;

class AuthenticationRepository implements AuthenticationRepositoryInterface
{
    /**
     * User
     */
    public function getUser(): ?User
    {
        if($this->isApiRequest()){
            return $this->getApiUser();
        }
        return $this->getWebUser();
    }

    public function getApiUser(): ?User
    {
        return Auth::guard('user')->user();
    }

    public function getWebUser(): ?User
    {
        return Auth::guard('user_web')->user();
    }

    public function getUserId(): ?int
    {
        return $this->getUser()?->id;
    }

    public function isUserLoggedIn(): bool
    {
        if ($this->isApiRequest()) {
            return Auth::guard('user')->check();
        }
        return Auth::guard('user_web')->check();;
    }

    /**
     * Seller
     */
    public function getSeller(): ?Seller
    {
        if($this->isApiRequest()){
            return $this->getApiSeller();
        }
        return $this->getWebSeller();
    }

    public function getApiSeller(): ?Seller
    {
        return Auth::guard('seller')->user();
    }

    public function getWebSeller(): ?Seller
    {
        return Auth::guard('seller_web')->user();
    }

    public function getSellerId(): ?int
    {
        return $this->getSeller()?->id;
    }

    public function isSellerLoggedIn(): bool
    {
        if ($this->isApiRequest()) {
            return Auth::guard('seller')->check();
        }
        return Auth::guard('seller_web')->check();;
    }

    /**
     * API istekleri için kontrol
     */
    private function isApiRequest(): bool
    {
        return request()->is('api/*');
    }
}