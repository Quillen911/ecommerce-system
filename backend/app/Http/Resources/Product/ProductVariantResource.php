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
            'price'          => $this->price,
            'price_cents'    => $this->price_cents,
            'stock_quantity' => $this->stock_quantity,
            'sold_quantity'  => $this->sold_quantity,
            'is_popular'     => $this->is_popular,
            'is_active'      => $this->is_active,
            'images'         => ProductVariantImageResource::collection($this->whenLoaded('variantImages')),
            'attributes'     => VariantAttributeResource::collection($this->whenLoaded('variantAttributes')),
        ];
    }
}
