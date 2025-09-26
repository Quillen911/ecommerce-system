<?php

namespace App\Http\Resources\Campaign;

use Illuminate\Http\Resources\Json\JsonResource;

class CampaignConditionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'campaign_id' => $this->campaign_id,
            'condition_type' => $this->condition_type,
            'condition_value' => $this->condition_value,
            'operator' => $this->operator,
        ];
    }
}
