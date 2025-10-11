<?php

namespace App\Http\Resources\Bag;

use Illuminate\Http\Resources\Json\JsonResource;

class BagSummaryResource extends JsonResource
{
    public function toArray($request)
    {
        $data = $this->resource;

        $totalCents    = (int) ($data['total_cents'] ?? 0);
        $cargoCents    = (int) ($data['cargo_price_cents'] ?? $data['cargo_cents'] ?? 0);
        $discountCents = (int) ($data['discount_cents'] ?? 0);
        $finalCents    = (int) ($data['final_price_cents'] ?? $data['final_cents'] ?? 0);

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
