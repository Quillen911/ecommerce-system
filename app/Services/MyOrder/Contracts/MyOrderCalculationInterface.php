<?php

namespace App\Services\MyOrder\Contracts;

interface MyOrderCalculationInterface
{
    public function calculateRefundAmount($item, $requestedQuantity): array;
    
    public function calculateRefundableItems($items, array $refundQuantitiesByItemId): array;
}