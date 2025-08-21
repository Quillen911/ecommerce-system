<?php

namespace App\Services\Campaigns\CampaignManager;

use App\Models\Campaign;
use App\Models\CampaignDiscount;
use App\Models\CampaignCondition;

class XBuyYPayCampaign extends BaseCampaign
{
    public function isApplicable(array $products): bool 
    {
        if(!$this->isCampaignActive()){
            return false;
        }
        $eligibleProducts = $this->productEligible($products);
        
        $min_bag = $this->getConditionValue('min_bag');
        if($min_bag){
            $total = collect($eligibleProducts)->sum(function($item) {
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
        $x = $discount_value['x'] ?? 0;
        $y = $discount_value['y'] ?? 0;
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

        $total_quantity = collect($eligible_products)->sum('quantity');

        if($total_quantity >= $x && $x > 0){
            $free_per_group = $x - $y;
            
            $full_groups = floor($total_quantity / $x);
            
            $total_free = $full_groups * $free_per_group;

            $sorted_products = collect($eligible_products)->sortBy('product.list_price');
            $free_products = collect();
            $remaining_free = $total_free;

            foreach($sorted_products as $item) {
                if($remaining_free <= 0) break;
                
                $take_quantity = min($item->quantity, $remaining_free);
                $free_products->push([
                    'product' => $item->product,
                    'quantity' => $take_quantity,
                    'price' => $item->product->list_price
                ]);                
                $remaining_free -= $take_quantity;
            }

            $total_discount = $free_products->sum(function($item) {
                return $item['price'] * $item['quantity'];
            });

            return [
                'description' => $this->campaign->description,
                'discount' => $total_discount,
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