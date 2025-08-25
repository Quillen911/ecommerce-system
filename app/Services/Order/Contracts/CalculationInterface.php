<?php

namespace App\Services\Order\Contracts;

interface CalculationInterface
{
    public function calculateTotal($products): float;

    public function calculateDiscount($products, $campaigns, $campaignManager): array;
    
    public function calculateCargoPrice(float $total): float;
    
    public function calculateDiscountRate(float $total, float $eligible_total): float;
}