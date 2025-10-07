<?php

namespace App\Http\Resources\Summary;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantImageSummaryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product_variant_id' => $this->product_variant_id,
            'image' => $this->image_url,
        ];
    }
}