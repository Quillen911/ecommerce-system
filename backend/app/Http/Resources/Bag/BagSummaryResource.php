<?php

namespace App\Http\Resources\Bag;

use Illuminate\Http\Resources\Json\JsonResource;

class BagSummaryResource extends JsonResource
{
    public function toArray($request)
    {
        $totalCents    = (int) ($this->resource['total_cents'] ?? 0);
        $cargoCents    = (int) ($this->resource['cargo_price_cents'] ?? 0);
        $discountCents = (int) ($this->resource['discount_cents'] ?? 0);
        $finalCents    = (int) ($this->resource['final_price_cents'] ?? 0);

        return [
            'total_cents'    => $totalCents,
            'total'          => $totalCents / 100,
            'cargo_cents'    => $cargoCents,
            'cargo'          => $cargoCents / 100,
            'discount_cents' => $discountCents,
            'discount'       => $discountCents / 100,
            'final_cents'    => $finalCents,
            'final'          => $finalCents / 100,
        ];
    }
}
