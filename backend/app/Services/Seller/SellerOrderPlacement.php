<?php

namespace App\Services\Seller;

use App\Models\OrderRefund;
use App\Models\OrderRefundItem;
use Illuminate\Support\Facades\DB;

class SellerOrderPlacement
{
    public function placeSellerOrder($orderItem, $payload, $refundAmount): void
    {
        DB::transaction(function () use ($orderItem, $payload, $refundAmount) {
            $newRefundedQuantity = ($orderItem->refunded_quantity ?? 0) + (int) $payload['quantity'];
            $newRefundedPrice = ($orderItem->refunded_price_cents ?? 0) + $refundAmount;

            $orderRefund = OrderRefund::create([
                'order_id'           => $orderItem->order_id,
                'user_id'            => $payload['user_id'] ?? $orderItem->order->user_id,
                'refund_total_cents' => $refundAmount,
            ]);

            $orderItem->update([
                'refunded_quantity'    => $newRefundedQuantity,
                'refunded_price_cents' => $newRefundedPrice,
                'status'               => $newRefundedQuantity >= $orderItem->quantity ? 'refunded' : 'partial_refunded',
                'payment_status'       => $newRefundedQuantity >= $orderItem->quantity ? 'refunded' : 'partial_refunded',
                'refunded_at'          => now(),
            ]);

            OrderRefundItem::create([
                'order_refund_id'      => $orderRefund->id,
                'order_item_id'        => $orderItem->id,
                'quantity'             => (int) $payload['quantity'],
                'refund_amount_cents'  => $refundAmount,
                'reason'               => $payload['reason'],
                'byWho'                => 'seller',
                'inspection_status'    => 'pending',
                'inspection_note'      => null,
            ]);

        });
    }
}