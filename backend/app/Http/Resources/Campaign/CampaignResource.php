<?php

namespace App\Http\Resources\Campaign;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Campaign\CampaignConditionResource;
use App\Http\Resources\Campaign\CampaignDiscountResource;

class CampaignResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'store_id' => $this->store_id,
            'code' => $this->code,
            'type' => $this->type,
            'discount_value' => $this->discount_value,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'starts_at' => $this->starts_at,
            'ends_at' => $this->ends_at,
        ];
    }
}