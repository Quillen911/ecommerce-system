<?php

namespace App\Services\Campaigns;

use App\Models\Campaign;
use App\Traits\Campaigns\SabahattinTrait;

class SabahattinAliCampaign implements CampaignInterface
{
    use SabahattinTrait;
    protected $campaign;

    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    public function isApplicable(array $products): bool 
    {
        return $this->isSabahattinAli($products);
    }

    public function calculateDiscount(array $products): array
    {
        $eligible = $this->isSabahattinAli($products);

        //En ucuz ürün için
        $totalQuantity = $eligible->sum('quantity'); 
        if($totalQuantity < 2) {
            return ['discount' => 0, 'description' =>''];
        }

        $discountRule = $this->campaign->discounts->where('applies_to', 'product')->first();
        $x = $discountRule ? json_decode($discountRule->discount_value)->x : 2;
        $y = $discountRule ? json_decode($discountRule->discount_value)->y : 1;

        $cheapest = $eligible->sortBy('product.list_price')->first();
        $discount = $cheapest ? $cheapest->product->list_price : 0;

        return [
            'description' => $this->campaign->description, 
            'discount' => $discount
        ];
    }

}   