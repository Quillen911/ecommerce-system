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

        return [
            'total_cents'    => $totalCents,
            'cargo_cents'    => $cargoCents,
            'discount_cents' => $discountCents,
            'final_cents'    => $finalCents,
            'per_item_price_cents' => $perItemPrice,
        ];
    }
}
