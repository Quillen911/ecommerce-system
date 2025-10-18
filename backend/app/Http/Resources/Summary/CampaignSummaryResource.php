<?php

namespace App\Http\Resources\Summary;

use Illuminate\Http\Resources\Json\JsonResource;

class CampaignSummaryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => $this->is_active,
        ];
    }
}