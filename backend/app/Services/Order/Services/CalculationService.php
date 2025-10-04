<?php

namespace App\Services\Order\Services;

use App\Services\Order\Contracts\CalculationInterface;

class CalculationService implements CalculationInterface
{
    protected $cargoThreshold;
    protected $cargoPrice;

    public function __construct()
    {
        $this->cargoThreshold = config('order.cargo.threshold');
        $this->cargoPrice = config('order.cargo.price');
    }
    
    public function calculateTotal($products): float
    {
        return $products->sum(function($items) {
            return $items->quantity * $items->unit_price_cents;
        });
    }

    public function calculateDiscount($products, $campaigns, $campaignManager): array
    {
        $bestCampaign = $campaignManager->getBestCampaigns($products->all(), $campaigns);
        
        return [
            'eligible_total' => $bestCampaign['eligible_total'] ?? 0,
            'discount' => $bestCampaign['discount'] ?? 0,
            'campaign_id' => $bestCampaign['campaign_id'] ?? null,
            'description' => $bestCampaign['description'] ?? null,
            'eligible_products' => $bestCampaign['eligible_products'] ?? collect(),
            'per_product_discount' => $bestCampaign['per_product_discount'] ?? collect(),
        ];
    }

    public function calculateCargoPrice(float $total): float
    {
        return $total >= $this->cargoThreshold ? 0 : $this->cargoPrice;
    }
}