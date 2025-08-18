<?php

namespace App\Services\Order\Contracts;

interface OrderServiceInterface
{
    public function createOrder($user, $products, $campaignManager, $selectedCreditCard): array;
    
    public function getOrder($userId, $orderId);
}