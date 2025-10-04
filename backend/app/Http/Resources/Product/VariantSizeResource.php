<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;


class VariantSizeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product_variant_id' => $this->product_variant_id,
            'size_option_id' => $this->size_option_id,
            'size_option' => new AttributeOptionResource($this->whenLoaded('sizeOption')),
            'sku' => $this->sku,
            'price_cents' => $this->price_cents,
            'is_active' => $this->is_active,
            'inventory' => new InventoryResource($this->whenLoaded('inventory')),
        ];
    }
}
