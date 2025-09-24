<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantImageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product_variant_id' => $this->product_variant_id,
            'image' => $this->image_url,
            'is_primary' => $this->is_primary,
            'sort_order' => $this->sort_order,
        ];
    }
}