<?php

namespace App\Services\Campaigns;

use App\Models\Campaign;
use App\Models\CampaignDiscount;
use App\Models\CampaignCondition;
use Illuminate\Support\Facades\Log;

class TwoHundreadsCampaign implements CampaignInterface
{

    protected $campaign;
    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    public function isApplicable(array $products): bool
    {

        $min_total = CampaignCondition::where('campaign_id', $this->campaign->id)->where('condition_type', 'min_total')->first()?->condition_value;
        $min_total = json_decode($min_total, true);

        $products = collect($products);
        $total = $products->sum(function($item) {
            return $item->product->list_price * $item->quantity;
        });

        return $total >= $min_total;
        
    }

    public function calculateDiscount(array $products): array
    {
        $products = collect($products);
        $total = $products->sum(function($items) {
            return $items->quantity * $items->product->list_price;
        });

        $discountRule = CampaignDiscount::where('campaign_id', $this->campaign->id)->first();
        $discountRate = $discountRule ? (json_decode($discountRule->discount_value)->discount) * $total : 0;
        
        return [
            'description' => $this->campaign->description,
            'discount' => $discountRate
        ];

    }
}