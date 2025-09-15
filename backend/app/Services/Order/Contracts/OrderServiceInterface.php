<?php

namespace App\Services\Order\Contracts;

interface OrderServiceInterface
{
    public function createOrder($user, $products, $campaignManager, $selectedCreditCard, $tempCardData = null, $saveNewCard = false): array;
    
    public function getOrder($user, $orderId);
}