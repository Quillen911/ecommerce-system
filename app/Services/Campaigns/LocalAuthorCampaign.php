<?php

namespace App\Services\Campaigns;

use App\Models\CampaignDiscount;
use App\Models\Campaign;
use App\Models\CampaignCondition;
class LocalAuthorCampaign implements CampaignInterface
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
        
        $localAuthor = CampaignCondition::where('campaign_id', $this->campaign->id)->where('condition_type', 'author')->first()?->condition_value;
        $localAuthor = json_decode($localAuthor, true);

        $products = collect($products);
        $eligible = $products->filter(function($item) use($localAuthor){
            return in_array($item->product->author, $localAuthor);
        });
        $totalEligible = $eligible->sum('quantity');
        return $totalEligible > 0;
    } 
    
    public function calculateDiscount(array $products): array
    {
        $localAuthor = CampaignCondition::where('campaign_id', $this->campaign->id)->where('condition_type', 'author')->first()?->condition_value;
        $localAuthor = json_decode($localAuthor, true);

        $products = collect($products);
        $eligible = $products->filter(function($item) use($localAuthor){
            return in_array($item->product->author, $localAuthor);
        });

        $total = $eligible->sum(function($item) {
            return $item->quantity * $item->product->list_price;
        });

        $discountRule = CampaignDiscount::where('campaign_id', $this->campaign->id)->first();
        $discountRate = $discountRule ? (json_decode($discountRule->discount_value)->discount) * $total : 0;
        return [
            'description' => $this->campaign->description, 
            'discount' => $discountRate
        ];
    }
}