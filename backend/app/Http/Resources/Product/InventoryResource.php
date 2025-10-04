<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class InventoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'variant_size_id' => $this->variant_size_id,
            'warehouse_id' => $this->warehouse_id,
            'on_hand' => $this->on_hand,
            'reserved' => $this->reserved,
            'available' => $this->available,
            'min_stock_level' => $this->min_stock_level,
        ];
    }
}
