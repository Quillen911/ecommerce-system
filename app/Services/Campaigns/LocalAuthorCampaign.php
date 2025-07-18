<?php

namespace App\Services\Campaigns;

class LocalAuthorCampaign implements CampaignInterface
{
    public function isApplicable(array $products): bool
    {
        $products = collect($products);
        $eligible = $products->filter(function($item) {
            return in_array($item->product->author, [
                'Yaşar Kemal', 
                'Oğuz Atay', 
                'Hakan Mengüç',
                'Sabahattin Ali',
                'Uğur Koşar',
                'Mert Arık',
                'Peyami Safa',
            ]);
        });
        $totalEligible = $eligible->sum('quantity');
        return $totalEligible > 1;
    } 
    
    public function calculateDiscount(array $products): array
    {
        $products = collect($products);
        $eligible = $products->filter(function($item) {
            return in_array($item->product->author, [
                'Yaşar Kemal', 
                'Oğuz Atay', 
                'Sabahattin Ali',
                'Hakan Mengüç',
                'Uğur Koşar',
                'Mert Arık',
                'Peyami Safa',
            ]);
        });

        $total = $eligible->sum(function($item) {
            return $item->quantity * $item->product->list_price;
        });

        $discount = $total * 0.05;
        return [
            'description' => 'Yerli yazar kitaplarında %5 indirim', 
            'discount' => $discount
        ];
    }
}