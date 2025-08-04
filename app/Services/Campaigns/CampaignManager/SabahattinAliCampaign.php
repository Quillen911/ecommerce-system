<?php

namespace App\Services\Campaigns\CampaignManager;

use App\Models\Campaign;
use App\Models\CampaignDiscount;
use App\Models\CampaignCondition;

class SabahattinAliCampaign implements CampaignInterface
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
    
        $condition = CampaignCondition::where('campaign_id', $this->campaign->id)->get();
        $condition_array = [];
        foreach($condition as $c){
            $condition_array[$c->condition_type] = json_decode($c->condition_value, true);
        }
        $author = $condition_array['author'] ?? null;
        $category = $condition_array['category'] ?? null;

        $products = collect($products);
        $eligible = $products->filter(function($item) use ($author, $category) {
            return $item->product->author == $author && $item->product->category?->category_title == $category;
        });
        
        $totalQuantity = $eligible->sum('quantity');
        return $totalQuantity > 1;
    }

    public function calculateDiscount(array $products): array
    {
        $condition = CampaignCondition::where('campaign_id', $this->campaign->id)->get();
        $condition_array = [];
        foreach($condition as $c){
            $condition_array[$c->condition_type] = json_decode($c->condition_value, true);
        }
        $author = $condition_array['author'] ?? null;
        $category = $condition_array['category'] ?? null;

        $products = collect($products);
        return $products->filter(function($item) use ($author, $category) {
            return $item->product->author == $author && $item->product->category?->category_title == $category;
        });

        //En ucuz ürün için
        $totalQuantity = $eligible->sum('quantity'); 
        if($totalQuantity < 2) {
            return ['discount' => 0, 'description' =>''];
        }
        $discountRule = CampaignDiscount::where('campaign_id', $this->campaign->id)->first();
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