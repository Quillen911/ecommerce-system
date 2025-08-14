<?php

namespace App\Services\Campaigns\CampaignManager;

class FixedCampaign extends BaseCampaign
{
    public function isApplicable(array $products): bool
    {
        if(!$this->isCampaignActive()){
            return false;
        }
        if(!$this->productEligible($products)){
            return false;
        }

        $min_bag = $this->getConditionValue('min_bag');
        if($min_bag){
            $total = collect($products)->sum(function($item) {
                return $item->product->list_price * $item->quantity;
            });
            if($total < $min_bag){
                return false;
            }
        }

        $author = $this->getConditionValue('author');
        if($author){
            $eligible = collect($products)->filter(function($item) use ($author) {
                return in_array($item->product->author, (array)$author);
            });
            if($eligible->sum('quantity') == 0){
                return false;
            }
        }

        $category = $this->getConditionValue('category');
        if($category){
            $eligible = collect($products)->filter(function($item) use ($category) {
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

        $discount_value = json_decode($discount_rule->discount_value, true);
        $discount_amount = $discount_value['amount'] ?? 0;

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
        if($eligible_products->sum('quantity') > 0) {
            return [
                'description' => $this->campaign->description,
                'discount' => $discount_amount,
                'campaign_id' => $this->campaign->id
            ];
        }

        return [
            'description' => $this->campaign->description,
            'discount' => 0,
            'campaign_id' => $this->campaign->id
        ];
    }
}