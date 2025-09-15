<?php

namespace App\Services\MyOrder\Contracts;

interface MyOrderCalculationInterface
{
    public function calculateRefundableItems($items, array $refundQuantitiesByItemId): array;
    
    public function calculateRefundAmount($item, $requestedQuantity): array;
}