<?php

namespace App\Services\Bag\Services;

use App\Services\Bag\Contracts\BagCalculationInterface;
use App\Services\Order\Services\CalculationService;

class BagCalculationService implements BagCalculationInterface
{
    protected $calculationService;
    
    public function __construct( CalculationService $calculationService)
    {
        $this->calculationService = $calculationService;
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
        $total = $this->calculationService->calculateTotal($bagItems);
        return $total;
    }

    public function calculateCargoPrice($total)
    {
        $cargoPrice = $this->calculationService->calculateCargoPrice($total);
        return $cargoPrice;
    }

    public function calculateDiscount($bestCampaign)
    {
        $discount = $bestCampaign['discount'] ?? 0;
        return $discount;
    }
}