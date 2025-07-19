<?php

namespace App\Services\Campaigns;

class TwoHundreadsCampaign implements CampaignInterface
{
    public function isApplicable(array $products): bool
    {
        $products = collect($products);
        $total = $products->sum(function($item) {
            return $item->product->list_price * $item->quantity;
        });
        return $total >= 200.00;
    }

    public function calculateDiscount(array $products): array
    {
        $products= collect($products);
        $eligible = $products->filter(function($items){
            $Total=$items->product->list_price * $items->quantity;
            return $Total;
        });
        $eligiblePrice =  $eligible->sum('Total') ;


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