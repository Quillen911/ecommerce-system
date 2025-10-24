<?php

namespace App\Http\Resources\Bag;

use Illuminate\Http\Resources\Json\JsonResource;

class BagSummaryResource extends JsonResource
{
    public function toArray($request)
    {
        $data = $this->resource;

        $totalCents    = $data['total_cents'] ?? 0;
        $cargoCents    = $data['cargo_price_cents'] ?? $data['cargo_cents'] ?? 0;
        $discountCents = $data['discount_cents'] ?? 0;
        $finalCents    = $data['final_price_cents'] ?? $data['final_cents'] ?? 0;
        $perItemPrice  = $data['per_item_price_cents'] ?? 0;
        $perItemCargoPrice = $data['per_item_cargo_price_cents'] ?? 0;
        $itemFinalPrice = $data['item_final_price_cents'] ?? 0;
        return [
            'total_cents'    => $totalCents,
            'per_item_price_cents' => $perItemPrice,
            'cargo_cents'    => $cargoCents,
            'per_item_cargo_price_cents' => $perItemCargoPrice,
            'discount_cents' => $discountCents,
            'final_cents'    => $finalCents,
            'item_final_price_cents' => $itemFinalPrice,
        ];
    }
}
