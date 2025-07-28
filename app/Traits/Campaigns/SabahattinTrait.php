<?php

namespace App\Traits\Campaigns;

use App\Models\Campaign;

trait SabahattinTrait
{
    public function isSabahattinAli(array $products): bool
    {
        $author = $this->campaign->conditions->where('condition_type', 'author')->first()?->condition_value;
        $category = $this->campaign->conditions->where('condition_type','category')->first()?->condition_value;

        
        $author = json_decode($author, true);
        $category = json_decode($category, true);

        $products = collect($products);
        $eligible = $products->filter(function($item) use ($author, $category) {
            return $item->product->author == $author && $item->product->category?->category_title == $category;
        });
        
        $totalQuantity = $eligible->sum('quantity');
        return $totalQuantity > 1;
    }
    
    public function getEligibleProducts(array $products)
    {
        $author = $this->campaign->conditions->where('condition_type', 'author')->first()?->condition_value;
        $category = $this->campaign->conditions->where('condition_type', 'category')->first()?->condition_value;

        
        $author = json_decode($author, true);
        $category = json_decode($category, true);

        $products = collect($products);
        return $products->filter(function($item) use ($author, $category) {
            return $item->product->author == $author && $item->product->category?->category_title == $category;
        });
    }
}
