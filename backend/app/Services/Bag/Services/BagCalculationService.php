<?php

namespace App\Services\Bag\Services;

use App\Services\Bag\Contracts\BagCalculationInterface;
use App\Services\Order\Services\CalculationService;

class BagCalculationService implements BagCalculationInterface
{
    private $cargoThreshold;
    protected $cargoPrice;
    
    public function __construct()
    {
        $this->cargoThreshold = config('order.cargo.threshold');
        $this->cargoPrice = config('order.cargo.price');
    }

    public function getBestCampaign($bagItems)
    {/*
        $campaigns = Campaign::where('is_active', 1)->get();
        $bestCampaign = $this->campaignManager->getBestCampaigns($bagItems->all(), $campaigns);
        return $bestCampaign;
        */
    }

    public function calculateTotal($bagItems)
    {
        return $bagItems->sum(function($items) {
            return $items->quantity * $items->unit_price_cents;
        });
    }

    public function calculateCargoPrice($total)
    {
        return $total >= $this->cargoThreshold ? 0 : $this->cargoPrice;
    }

    public function calculateDiscount($bestCampaign)
    {
        $discount = $bestCampaign['discount'] ?? 0;
        return $discount;
    }
}