<?php

namespace App\Http\Resources\Bag;

use Illuminate\Http\Resources\Json\JsonResource;

class BagResource extends JsonResource
{
    public function toArray($request)
    {
        $appliedCampaign = $this['applied_campaign'] ?? null;
        $discountItems   = $this['discount_items'] ?? [];

        return [
            'products'        => BagItemResource::collection($this['products']),
            'totals'          => new BagSummaryResource($this),
            'applied_campaign' => $appliedCampaign
                ? new AppliedCampaignResource([
                    'campaign'       => $appliedCampaign,
                    'discount_cents' => $this['discount_cents'] ?? 0,
                    'items'          => $discountItems,
                ])
                : null,
            'campaigns'       => $this['campaigns'] ?? [],
        ];
    }
}
