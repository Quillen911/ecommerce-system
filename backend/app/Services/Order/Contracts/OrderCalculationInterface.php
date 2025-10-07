<?php

namespace App\Services\Order\Contracts;

interface OrderCalculationInterface
{
    public function calculateRefundableItems($items, array $refundQuantitiesByItemId): array;
    
    public function calculateRefundAmount($item, $requestedQuantity): array;
}