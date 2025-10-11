<?php

namespace App\Http\Resources\Bag;

use Illuminate\Http\Resources\Json\JsonResource;

class AppliedCampaignResource extends JsonResource
{
    public function toArray($request)
    {
        $campaign      = $this->resource['campaign'] ?? $this->resource;
        $discountCents = $this->resource['discount_cents'] ?? 0;
        $discountItems = $this->resource['items'] ?? [];

        if (! $campaign) {
            return null;
        }

        return [
            'id'             => $campaign->id,
            'name'           => $campaign->name,
            'type'           => $campaign->type,
            'description'    => $campaign->description,
            'discount_cents' => $discountCents,
            'ends_at'        => $campaign->ends_at ? $campaign->ends_at->toIso8601String() : null,
            'discount_items' => $discountItems,
        ];
    }
}
