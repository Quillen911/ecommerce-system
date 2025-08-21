<?php

namespace App\Services\Campaigns\CampaignManager;

class PercentageCampaign extends BaseCampaign
{

    public function isApplicable(array $products): bool
    {
        if(!$this->isCampaignActive()){
            return false;
        }

        $eligibleProducts = $this->productEligible($products);
        

        $min_bag = $this->getConditionValue('min_bag');
        if($min_bag){
            $total = collect($eligibleProducts)->sum(function($item) use ($min_bag){
                return $item->product->list_price * $item->quantity;
            });
            if($total < $min_bag){
                return false;
            }
        }

        $author = $this->getConditionValue('author');
        if($author){
            $eligible = collect($eligibleProducts)->filter(function($item) use ($author) {
                return in_array($item->product->author, (array)$author);
            });
            if($eligible->sum('quantity') == 0){
                return false;
            }
            
        }

        $category = $this->getConditionValue('category');
        if($category){
            $eligible = collect($eligibleProducts)->filter(function($item) use ($category) {
                return $item->product->category?->category_title == $category;
            }); 
            if($eligible->sum('quantity') == 0){
                return false;
            }
        }

        return true;
    }

    public function calculateDiscount(array $products): array 
    {

        $discount_rule = $this->getDiscountRule();
        if (!$discount_rule) {
            return ['description' => '', 'discount' => 0];
        }



        $discount_value = is_string($discount_rule->discount_value) ? json_decode($discount_rule->discount_value, true) : $discount_rule->discount_value;
        $discount_rate = is_array($discount_value) ? ($discount_value['percentage'] / 100 ?? 0) : ((float)$discount_value / 100);

        $eligible_products = $this->productEligible($products);
        
        $author = $this->getConditionValue('author');
        if($author){
            $eligible_products = collect($eligible_products)->filter(function($item) use ($author) {
                return in_array($item->product->author, (array)$author);
            });
        }

        $category = $this->getConditionValue('category');
        if($category){
            $eligible_products = collect($eligible_products)->filter(function($item) use ($category) {
                return $item->product->category?->category_title == $category;
            });
        }
        
        $eligible_products = collect($eligible_products)->unique('id');
        
        $eligible_total = $eligible_products->sum(function($item) {
            return $item->product->list_price * $item->quantity;
        });
        
        
        return [
            'description' => $this->campaign->description,
            'discount' => $eligible_total * $discount_rate,
            'campaign_id' => $this->campaign->id,
            'store_id' => $this->campaign->store_id
        ];
    }
    
}