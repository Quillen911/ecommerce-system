<?php

namespace App\Services\Campaigns\CampaignManager;

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

        
        $condition = CampaignCondition::where('campaign_id', $this->campaign->id)
            ->where('condition_type', 'author')
            ->first();

        if (!$condition) {
            return false; 
        }

        $localAuthor = json_decode($condition->condition_value, true) ?? [];

        $products = collect($products);
        $eligible = $products->filter(function($item) use($localAuthor){
            return in_array($item->product->author, $localAuthor);
        });
        return $eligible->count() > 0;
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