<?php

namespace App\Http\Resources\Bag;

use Illuminate\Http\Resources\Json\JsonResource;

class BagItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'               => $this->id,
            'variant_id'       => $this->variant_id,
            'variant_size_id'  => $this->variant_size_id,
            'product_title'    => $this->product_title,
            'quantity'         => $this->quantity,
            'unit_price_cents' => $this->unit_price_cents,
            'store_id'         => $this->store_id,
            'sizes'            => $this->whenLoaded('variantSize', function () {
                $variantSize = $this->variantSize->toArray();
                if (! empty($variantSize['size_option'])) {
                    $variantSize['size_option_value'] = $variantSize['size_option']['value'];
                }
                $variantSize['category'] = $variantSize['product_variant']['product']['category'];

                if (! empty($variantSize['product_variant']['variant_images'])) {
                    $variantSize['product_variant']['variant_images'] = array_map(function ($image) {
                        $image['image_url'] = asset('storage/productImages/' . $image['image']);
                        unset($image['image']);

                        return $image;
                    }, $variantSize['product_variant']['variant_images']);
                }

                return $variantSize;
            }),
        ];
    }
}
