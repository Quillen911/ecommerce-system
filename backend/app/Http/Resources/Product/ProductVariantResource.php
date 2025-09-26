<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'             => $this->id,
            'sku'            => $this->sku,
            'price'          => $this->price,
            'price_cents'    => $this->price_cents,
            'stock_quantity' => $this->stock_quantity,
            'is_popular'     => $this->is_popular,
            'images'         => ProductVariantImageResource::collection($this->whenLoaded('variantImages')),
            'attributes'     => VariantAttributeResource::collection($this->whenLoaded('variantAttributes')),
        ];
    }
}
