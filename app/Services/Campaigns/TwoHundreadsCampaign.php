<?php

namespace App\Services\Campaigns;

class TwoHundreadsCampaign implements CampaignInterface
{
    public function isApplicable(array $products): bool
    {
        $products= collect($products);
        $eligible = $products->filter(function($items){
            return $items->product->list_price * $items->quantity;
        });
        $eligiblePrice =  $eligible->sum('quantity') * $eligible->sum('product.list_price') ;
        return $eligiblePrice >= 200; 
    }

    public function calculateDiscount(array $products): array
    {
        $products= collect($products);
        $eligible = $products->filter(function($items){
            return $items->product->list_price * $items->quantity;
        });
        $eligiblePrice =  $eligible->sum('quantity')* $eligible->sum('product.list_price') ;


        $total = $eligible->sum(function($items) {
            return $items->quantity * $items->product->list_price;
        });

        $discount = $total * 0.05;
        return [
            'description' => '200 TL ve üzeri alışverişlerde sipariş toplamına %5 indirim',
            'discount' => $discount
        ];

    }
}