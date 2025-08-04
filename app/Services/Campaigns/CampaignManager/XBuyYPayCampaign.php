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
        
        $min_bag = $this->getConditionValue('min_bag');
        if($min_bag){
            $total = collect($products)->sum(function($item) use ($min_bag){
                return $item->product->list_price * $item->quantity;
            });
            return $total >= $min_bag;
        }

        $author = $this->getConditionValue('author');
        if($author){
            $eligible = collect($products)->filter(function($item) use ($author) {
                return in_array($item->product->author, (array)$author);
            });
            return $eligible->sum('quantity') > 0;
        }

        $category = $this->getConditionValue('category');
        if($category){
            $eligible = collect($products)->filter(function($item) use ($category) {
                return $item->product->category?->category_title == $category;
            }); 
            return $eligible->sum('quantity') > 0;
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
        $x = $discount_value['x'] ?? 0;
        $y = $discount_value['y'] ?? 0;
        $eligible_products = collect($products);

        $min_bag = $this->getConditionValue('min_bag');
        if($min_bag){
            $eligible_products = $eligible_products->filter(function($item) use ($min_bag){
                return $item->product->list_price * $item->quantity >= $min_bag;
            });
        }

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

        if($total_quantity >= $x && $x > 0 ){
            $paid_quantitiy = min($total_quantity, $y);

            $free_quantity = $total_quantity - $paid_quantitiy;


            $total_discount = $eligible_products->sum(function($item) use ($free_quantity, $total_quantity) {
                $item_free_ratio = $item->quantity / $total_quantity;
                $item_free_quantity = $free_quantity * $item_free_ratio;
                return $item_free_quantity * $item->product->list_price;
            });

            $cheapest = $eligible_products->sortBy('product.list_price')->first();
            $discount = $cheapest ? $cheapest->product->list_price : 0;
            $total_discount = $total_discount + ($discount * $free_quantity);


            return [
                'description' => $this->campaign->description,
                'discount' => $total_discount
            ];
        }
        return [
            'description' => $this->campaign->description,
            'discount' => 0
        ];
    }
}