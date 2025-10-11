<?php

namespace App\Http\Resources\Bag;

use Illuminate\Http\Resources\Json\JsonResource;

class AppliedCampaignResource extends JsonResource
{
    public function toArray($request)
    {
        $campaign      = $this->resource['campaign'] ?? $this->resource;
        $discountCents = (int) ($this->resource['discount_cents'] ?? 0);
        $items         = collect($this->resource['items'] ?? []);

        if (! $campaign) {
            return null;
        }

        return [
            'id'             => $campaign->id,
            'name'           => $campaign->name,
            'type'           => $campaign->type,
            'description'    => $campaign->description,
            'discount_cents' => $discountCents,
            'discount'       => $discountCents / 100,
            'ends_at'        => optional($campaign->ends_at)->toIso8601String(),
            'items'          => BagDiscountItemResource::collection($items),
        ];
    }
}
