<?php

namespace App\Http\Resources\Bag;

use Illuminate\Http\Resources\Json\JsonResource;

class BagResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                 => $this->id,
            'products'           => BagItemResource::collection($this->whenLoaded('bagItems')),
            'totals'             => [
                'total_cents'      => (int) $this->total_cents,
                'total'            => (float) $this->total,
                'cargo_cents'      => (int) $this->cargo_price_cents,
                'cargo'            => (float) $this->cargoPrice,
                'discount_cents'   => (int) ($this->campaign_discount_cents ?? 0),
                'discount'         => (float) (($this->campaign_discount_cents ?? 0) / 100),
                'final_cents'      => (int) $this->final_price_cents,
                'final'            => (float) $this->final_price,
            ],
            'applied_campaign'   => $this->when($this->relationLoaded('campaign') && $this->campaign, function () {
                return [
                    'id'          => $this->campaign->id,
                    'name'        => $this->campaign->name,
                    'type'        => $this->campaign->type,
                    'description' => $this->campaign->description,
                    'discount'    => [
                        'cents'  => (int) ($this->campaign_discount_cents ?? 0),
                        'amount' => (float) (($this->campaign_discount_cents ?? 0) / 100),
                    ],
                    'ends_at'     => optional($this->campaign->ends_at)->toIso8601String(),
                ];
            }),
        ];
    }
}
