<?php

namespace App\Services\Campaigns\CampaignManager;

class PercentageCampaign extends BaseCampaign
{
    public function isApplicable(array $products): bool
    {
        if(!$this->isCampaignActive()){
            return false;
        }

        $condition_logic = strtoupper($this->campaign->condition_logic ?? 'AND');
        $conditions_met = ($condition_logic === 'OR') ? false : true;


        $min_bag = $this->getConditionValue('min_bag');
        if($min_bag){
            $total = collect($products)->sum(function($item) use ($min_bag){
                return $item->product->list_price * $item->quantity;
            });
            $conditions_met = $condition_logic == 'AND' ? $conditions_met && $total >= $min_bag : $conditions_met || $total >= $min_bag;
        }

        $author = $this->getConditionValue('author');
        if($author){
            $eligible = collect($products)->filter(function($item) use ($author) {
                return in_array($item->product->author, (array)$author);
            });
            $conditions_met = $condition_logic == 'AND' ? $conditions_met && $eligible->sum('quantity') > 0 : $conditions_met || $eligible->sum('quantity') > 0;
            
        }

        $category = $this->getConditionValue('category');
        if($category){
            $eligible = collect($products)->filter(function($item) use ($category) {
                return $item->product->category?->category_title == $category;
            }); 
            $conditions_met = $condition_logic == 'AND' ? $conditions_met && $eligible->sum('quantity') > 0 : $conditions_met || $eligible->sum('quantity') > 0;
        }

        return $conditions_met;
    }

    public function calculateDiscount(array $products): array 
    {
        $discount_rule = $this->getDiscountRule();
        if (!$discount_rule) {
            return ['description' => '', 'discount' => 0];
        }

        $discount_value = json_decode($discount_rule->discount_value, true);
        $discount_rate = $discount_value['percentage'] / 100 ?? 0;

        $eligible_products = collect($products);
        
        $author = $this->getConditionValue('author');
        if($author){
            $eligible_products = $eligible_products->filter(function($item) use ($author) {
                return in_array($item->product->author, (array)$author);
            });
        }

        $category = $this->getConditionValue('category');
        if($category){
            $eligible_products = $eligible_products->filter(function($item) use ($category) {
                return $item->product->category?->category_title == $category;
            });
        }
        


        $eligible_total = $eligible_products->sum(function($item) {
            return $item->product->list_price * $item->quantity;
        });

        return [
            'description' => $this->campaign->description,
            'discount' => $eligible_total * $discount_rate,
            'campaign_id' => $this->campaign->id
        ];
    }
    
}