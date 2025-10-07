<?php

namespace App\Http\Resources\Bag;

use Illuminate\Http\Resources\Json\JsonResource;

class BagResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'bag_id' => $this->bag_id,
            'variant_id' => $this->variant_id,
            'variant_size_id' => $this->variant_size_id,
            'product_title' => $this->product_title,
            'quantity' => $this->quantity,
            'unit_price_cents' => $this->unit_price_cents,
            'store_id' => $this->store_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'sizes' => $this->whenLoaded('variantSize', function () {
                $variantSize = $this->variantSize->toArray();

                if (isset($variantSize['product_variant']['variant_images'])) {
                    $variantSize['product_variant']['variant_images'] = array_map(function ($image) {
                        $image['image_url'] = asset('storage/productImages/' . $image['image']);
                        unset($image['image']);
                        return $image;
                    }, $variantSize['product_variant']['variant_images']);
                }
                
                return $variantSize;
            })
        ];
    }
}