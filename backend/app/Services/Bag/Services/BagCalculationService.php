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

    public function getBestCampaign($bagItems, $user)
    {/*
        $campaigns = Campaign::where('is_active', 1)->get();
        $bestCampaign = $this->campaignManager->getBestCampaigns($bagItems->all(), $campaigns, $user);
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

    public function calculateItemCargoPrice($bagItems)
    {
        $total = $this->calculateTotal($bagItems);
        if($total >= $this->cargoThreshold){
            return collect($bagItems)->mapWithKeys(function($item) {
                return [$item->id => 0];
            });
        }
        $cargoPrice = collect($bagItems)->mapWithKeys(function($item) use ($total) {
            return [$item->id => round(($item->quantity * $item->unit_price_cents) * $this->cargoPrice /$total)];
        });
        return $cargoPrice;
    }

    public function itemFinalPrice(array $perItemCargoPrice, array $perItemPrice, array $discountItems)
    {
        $discounts = collect($discountItems)->mapWithKeys(function ($item, $bagItemId) {
            return [$item['bag_item_id'] => (int) ($item['discount_cents'] ?? 0)];
        });
        
        $cargoShares = collect($perItemCargoPrice)->mapWithKeys(function ($value, $bagItemId) {
            return [$bagItemId => (int) $value];
        });
        
        $productPrices = collect($perItemPrice)->mapWithKeys(function ($value, $bagItemId) {
            return [$bagItemId => (int) $value];
        });
        //dd($perItemCargoPrice, $perItemPrice, $discountItems, $discounts, $cargoShares, $productPrices);
       
        return $productPrices->mapWithKeys(function ($price, $bagItemId) use ($cargoShares, $discounts) {
            $cargo     = $cargoShares->get($bagItemId, 0);
            $discount  = $discounts->get($bagItemId, 0);

            return [$bagItemId => $price + $cargo - $discount];
        });
    }
}