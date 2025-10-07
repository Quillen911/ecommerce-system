<?php

namespace App\Http\Resources\Summary;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Summary\ProductVariantImageSummaryResource;
use App\Http\Resources\Summary\VariantSizeSummaryResource;

class ProductVariantSummaryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'             => $this->id,
            'slug'           => $this->slug,
            'color_name'     => $this->color_name,
            'color_code'     => $this->color_code,
            'price_cents'    => $this->price_cents,
            'images'         => ProductVariantImageSummaryResource::collection($this->whenLoaded('variantImages')),
            'sizes'          => VariantSizeSummaryResource::collection($this->whenLoaded('variantSizes')),
            
        ];
    }
}