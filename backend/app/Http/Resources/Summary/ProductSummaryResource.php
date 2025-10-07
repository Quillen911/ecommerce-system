<?php

namespace App\Http\Resources\Summary;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Summary\ProductVariantSummaryResource;

class ProductSummaryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'               => $this->id,
            'store_id'         => $this->store_id,
            'title'            => $this->title,
            'slug'             => $this->slug,
            'category_id'      => $this->category_id,
            'variants'         => ProductVariantSummaryResource::collection($this->whenLoaded('variants')),
        ];
    }
}