<?php

namespace App\Http\Resources\Bag;

use Illuminate\Http\Resources\Json\JsonResource;

class BagDiscountItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'bag_item_id'            => $this['bag_item_id'],
            'product_id'             => $this['product_id'],
            'quantity'               => $this['quantity'],
            'unit_price_cents'       => $this['unit_price_cents'],
            'line_total_cents'       => $this['unit_price_cents'] * $this['quantity'],
            'discount_cents'         => $this['discount_cents'],
        ];
    }
}
