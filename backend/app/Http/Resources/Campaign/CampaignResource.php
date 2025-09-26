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
            'id' => $this->id,
            'name' => $this->name,
            'store_id' => $this->store_id,
            'store_name' => $this->store_name,
            'description' => $this->description,
            'type' => $this->type,
            'is_active' => $this->is_active,
            'priority' => $this->priority,
            'usage_limit' => $this->usage_limit,
            'usage_limit_for_user' => $this->usage_limit_for_user,
            'starts_at' => $this->starts_at,
            'ends_at' => $this->ends_at,
            'conditions' => CampaignConditionResource::collection($this->whenLoaded('conditions')),
            'discounts' => CampaignDiscountResource::collection($this->whenLoaded('discounts')),
        ];
    }
}