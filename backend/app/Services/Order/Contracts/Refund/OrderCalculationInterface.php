<?php

namespace App\Services\Order\Contracts\Refund;

interface OrderCalculationInterface
{
    public function calculateRefundableItems($items, array $payloadItems): array;
    
    public function calculateRefundAmount($orderItem, $requestedQuantity): array;
}