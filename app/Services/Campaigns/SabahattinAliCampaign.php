<?php

namespace App\Services\Campaigns;

class SabahattinAliCampaign implements CampaignInterface
{
    public function isApplicable(array $products): bool 
    {
        //Ürün Kontrolü
        $products = collect($products);
        $eligible = $products->filter(function($item) {
            return $item->product->author == 'Sabahattin Ali' && $item->product->category?->category_title == 'Roman';
        });
        $totalQuantity = $eligible->sum('quantity');
        return $totalQuantity > 1;
    }

    public function calculateDiscount(array $products): array
    {
        $products = collect($products);
        $eligible = $products->filter(function ($item) {
            return $item->product->author == 'Sabahattin Ali' && $item->product->category?->category_title == 'Roman';
        });

        //En ucuz ürün için
        $totalQuantity = $eligible->sum('quantity');
        if($totalQuantity < 2) 
        {
            return ['discount' => 0, 'description' =>''];
        }

        $cheapest = $eligible->sortBy('product.list_price')->first();
        $discount = $cheapest ? $cheapest->product->list_price : 0;

        return [
            'description' => 'Sabahattin Ali Romanlarında 2 al 1 öde kampanyası', 
            'discount' => $discount
        ];
    }

}   