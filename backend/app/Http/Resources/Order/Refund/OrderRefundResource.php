<?php

namespace App\Http\Resources\Order\Refund;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderRefundResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                => $this->id,
            'order_id'          => $this->order_id,
            'user_id'           => $this->user_id,
            'status'            => $this->status,
            'reason'            => $this->reason,
            'customer_note'     => $this->customer_note,
            'shipping_provider' => $this->shipping_provider,
            'tracking_number'   => $this->tracking_number,
            'refund_total_cents'=> $this->refund_total_cents,
            'timeline'          => [
                'requested_at'  => $this->created_at,
                'picked_up_at'  => $this->picked_up_at,
                'in_transit_at' => $this->in_transit_at,
                'received_at'   => $this->received_at,
                'refunded_at'   => $this->refunded_at,
            ],
            'items'             => OrderRefundItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
