<?php

namespace App\Http\Resources\Campaign;

use Illuminate\Http\Resources\Json\JsonResource;

class CampaignDiscountResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'campaign_id' => $this->campaign_id,
            'discount_type' => $this->discount_type,
            'discount_value' => $this->discount_value,
        ];
    }
}