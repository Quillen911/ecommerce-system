<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use App\Models\Seller;

interface AuthenticationRepositoryInterface
{
    /**
     * Mevcut kullanıcıyı getir (API veya Web)
     */
    public function getUser(): ?User;

    /**
     * API kullanıcısını getir
     */
    public function getApiUser(): ?User;

    /**
     * Web kullanıcısını getir
     */
    public function getWebUser(): ?User;

    /**
     * Mevcut kullanıcının ID'sini getir
     */
    public function getUserId(): ?int;
    
    /**
     * Kullanıcının giriş yapıp yapmadığını kontrol et
     */
    public function isUserLoggedIn(): bool;


    /**
     * Mevcut satıcıyı getir (API veya Web)
     */
    public function getSeller(): ?Seller;

    /**
     * API satıcısını getir
     */
    public function getApiSeller(): ?Seller;

    /**
     * Web satıcısını getir
     */
    public function getWebSeller(): ?Seller;

    /**
     * Mevcut satıcının ID'sini getir
     */
    public function getSellerId(): ?int;

    /**
     * Satıcının giriş yapıp yapmadığını kontrol et
     */
    public function isSellerLoggedIn(): bool;
    
}