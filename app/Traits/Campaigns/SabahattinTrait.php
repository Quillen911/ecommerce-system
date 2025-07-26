<?php

namespace App\Traits\Campaigns;

use App\Models\Campaign;

trait SabahattinTrait
{
    protected $campaign;

    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    public function isSabahattinAli(array $products): bool
    {
        $author = $this->campaign->conditions->where('condition_type', 'author')->first()?->condition_value;
        $category = $this->campaign->conditions->where('condition_type','category')->first()?->condition_value;

        $products = collect($products);
        $eligible = $products->filter(function($item) {
            return $item->product->author == $author && $item->product->category?->category_title == $category;
        });
        $totalQuantity = $eligible->sum('quantity');
        return $totalQuantity > 1;
    }
}
