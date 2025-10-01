<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantSummaryResource extends JsonResource
{
    public function toArray($request)
    {
        $firstImage = $this->variantImages->first();

        return [
            'id'    => $this->id,
            'slug'  => $this->slug,
            'thumbnail' => $firstImage ? $firstImage->image_url : null,
        ];
    }
}
