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

        $condition_logic = strtoupper($this->campaign->condition_logic ?? 'AND');
        $conditions_met = ($condition_logic === 'OR') ? false : true;

        $min_bag = $this->getConditionValue('min_bag');
        if($min_bag){
            $total = collect($products)->sum(function($item) {
                return $item->product->list_price * $item->quantity;
            });
            $conditions_met = $condition_logic == 'AND' ? 
                $conditions_met && ($total >= $min_bag) : 
                $conditions_met || ($total >= $min_bag);
        }

        $author = $this->getConditionValue('author');
        if($author){
            $eligible = collect($products)->filter(function($item) use ($author) {
                return in_array($item->product->author, (array)$author);
            });
            $conditions_met = $condition_logic == 'AND' ? 
                $conditions_met && ($eligible->sum('quantity') > 0) : 
                $conditions_met || ($eligible->sum('quantity') > 0);
        }

        $category = $this->getConditionValue('category');
        if($category){
            $eligible = collect($products)->filter(function($item) use ($category) {
                return $item->product->category?->category_title == $category;
            }); 
            $conditions_met = $condition_logic == 'AND' ? 
                $conditions_met && ($eligible->sum('quantity') > 0) : 
                $conditions_met || ($eligible->sum('quantity') > 0);
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
        $x = $discount_value['x'] ?? 0;
        $y = $discount_value['y'] ?? 0;
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

        $total_quantity = $eligible_products->sum('quantity');

        if($total_quantity >= $x && $x > 0){
            $free_per_group = $x - $y;
            
            $full_groups = floor($total_quantity / $x);
            
            $total_free = $full_groups * $free_per_group;

            $sorted_products = $eligible_products->sortBy('product.list_price');
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