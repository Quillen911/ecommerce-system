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
            'variant_size_id' => $this->variant_size_id,
            'product_title' => $this->product_title,
            'quantity' => $this->quantity,
            'unit_price_cents' => $this->unit_price_cents,
            'store_id' => $this->store_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'sizes' => $this->whenLoaded('variantSize', function () {
                $variantSize = $this->variantSize->toArray();
                
                // Resimleri düzenle
                if (isset($variantSize['variants']['images'])) {
                    $variantSize['variants']['images'] = array_map(function ($image) {
                        $image['image_url'] = asset('storage/productImages/' . $image['image']);
                        return $image;
                    }, $variantSize['variants']['images']);
                }
                
                return $variantSize;
            })
        ];
    }
}