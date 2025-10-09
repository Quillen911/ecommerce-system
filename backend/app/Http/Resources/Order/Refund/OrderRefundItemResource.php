<?php

namespace App\Http\Resources\Order\Refund;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderRefundItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'           => $this->id,
            'order_item_id'=> $this->order_item_id,
            'quantity'     => $this->quantity,
            'refund_amount'=> $this->refund_amount_cents,
            'status'       => $this->inspection_status,
        ];
    }
}
