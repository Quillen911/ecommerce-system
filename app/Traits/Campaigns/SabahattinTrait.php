<?php

namespace App\Traits\Campaigns;

use App\Models\Campaign;
use App\Models\CampaignCondition;
trait SabahattinTrait
{
    public function isSabahattinAli(array $products): bool
    {   

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
    
    public function getEligibleProducts(array $products)
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
    }
}
