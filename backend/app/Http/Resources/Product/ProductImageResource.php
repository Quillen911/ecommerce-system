<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductImageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'image' => $this->image_url,
            'is_primary' => $this->is_primary,
            'sort_order' => $this->sort_order,
        ];
    }
}