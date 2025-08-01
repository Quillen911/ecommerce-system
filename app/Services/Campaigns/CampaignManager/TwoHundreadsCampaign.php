<?php

namespace App\Services\Campaigns\CampaignManager;

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
        if($this->campaign->starts_at > now() || $this->campaign->ends_at < now()){
            $this->campaign->is_active = 0;
            $this->campaign->save();
            return false;
        }
        $user = auth()->user();
        if($user){

            $userUsage = $this->campaign->user_usage ?? [];
            $userUsageCount = $userUsage[$user->id] ?? 0;

            if($userUsageCount >= $this->campaign->usage_limit_for_user){
                $this->campaign->user_activity = 0;
                $this->campaign->save();
                return false;
            }
        }
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