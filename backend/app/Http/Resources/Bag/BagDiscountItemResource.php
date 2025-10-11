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
            'quantity'               => (int) $this['quantity'],
            'unit_price_cents'       => (int) $this['unit_price_cents'],
            'line_total_cents'       => (int) $this['line_total_cents'],
            'discount_cents'         => (int) $this['discount_cents'],
            'discount'               => $this['discount_cents'] / 100,
            'discounted_total_cents' => (int) $this['discounted_total_cents'],
            'discounted_total'       => $this['discounted_total_cents'] / 100,
        ];
    }
}
