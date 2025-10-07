<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Summary\ProductSummaryResource;

class OrderItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                     => $this->id,
            'order_id'               => $this->order_id,
            'product_id'             => $this->product_id,
            'variant_size_id'        => $this->variant_size_id,
            'store_id'               => $this->store_id,
            'product_title'          => $this->product_title,
            'product_category_title' => $this->product_category_title,
            'selected_options'       => $this->selected_options,
            'size_name'              => $this->size_name,
            'color_name'             => $this->color_name,
            'quantity'               => $this->quantity,
            'refunded_quantity'      => $this->refunded_quantity,
            'price_cents'            => $this->price_cents,
            'discount_price_cents'   => $this->discount_price_cents,
            'paid_price_cents'       => $this->paid_price_cents,
            'tax_rate'               => $this->tax_rate,
            'tax_amount_cents'       => $this->tax_amount_cents,
            'payment_transaction_id' => $this->payment_transaction_id,
            'status'                 => $this->status,
            'refunded_price_cents'   => $this->refunded_price_cents,
            'payment_status'         => $this->payment_status,
            'refunded_at'            => $this->refunded_at,
            'created_at'             => $this->created_at,
            'updated_at'             => $this->updated_at,
            'product'                => new ProductSummaryResource($this->whenLoaded('product')),
        ];
    }
}
