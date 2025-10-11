<?php

namespace App\Http\Resources\Campaign;

use Illuminate\Http\Resources\Json\JsonResource;

class CampaignResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'store_id' => $this->store_id,
            'code' => $this->code,
            'type' => $this->type,
            'discount_value' => $this->discount_value,
            'buy_quantity' => $this->buy_quantity,
            'pay_quantity' => $this->pay_quantity,
            'min_subtotal' => $this->min_subtotal,
            'usage_limit' => $this->usage_limit,
            'usage_count' => $this->usage_count,
            'is_active' => $this->is_active,
            'starts_at' => $this->starts_at,
            'ends_at' => $this->ends_at,
            'products' => CampaignProductResource::collection($this->campaignProducts),
            'categories' => CampaignCategoryResource::collection($this->campaignCategories),
        ];
    }
}