<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'             => $this->id,
            'product_id'     => $this->product_id,
            'sku'            => $this->sku,
            'slug'           => $this->slug,
            'color_name'     => $this->color_name,
            'color_code'     => $this->color_code,
            'price_cents'    => $this->price_cents,
            'is_popular'     => $this->is_popular,
            'is_active'      => $this->is_active,
            'images'         => ProductVariantImageResource::collection($this->whenLoaded('variantImages')),
            'sizes'          => VariantSizeResource::collection($this->whenLoaded('variantSizes')),
            
        ];
    }
}
