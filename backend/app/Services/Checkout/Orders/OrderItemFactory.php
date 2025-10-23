<?php

namespace App\Services\Checkout\Orders;

use App\Models\CheckoutSession;
use App\Models\Order;
use App\Repositories\Contracts\OrderItem\OrderItemRepositoryInterface;
use Illuminate\Support\Collection;

class OrderItemFactory
{
    public function __construct(
        private readonly OrderItemRepositoryInterface $orderItems,
    ) {}

    public function createMany(Order $order, CheckoutSession $session): Collection
    {
        $items = collect();

        $bagSnapshot = $session->bag_snapshot ?? [];
        $bagItems    = $bagSnapshot['items'] ?? [];
        $discounts   = collect($bagSnapshot['applied_campaign']['discount_items'] ?? [])
            ->keyBy('bag_item_id');

        foreach ($bagItems as $snapshot) {
            $bagItemId = $snapshot['bag_item_id'];
            $discount  = $discounts->get($bagItemId);

            $paidPriceCents     = $discount['discounted_total_cents'] ?? $snapshot['total_price_cents'];
            $discountPriceCents = $discount['discount_cents'] ?? 0;

            $items->push(
                $this->orderItems->create([
                    'order_id'               => $order->id,
                    'product_id'             => $snapshot['product_id'],
                    'variant_size_id'        => $snapshot['variant_size_id'],
                    'store_id'               => $snapshot['store_id'] ?? null,
                    'product_title'          => $snapshot['product_title'],
                    'product_category_title' => $snapshot['product_category_title'],
                    'size_name'              => $snapshot['size_name'],
                    'color_name'             => $snapshot['color_name'],
                    'quantity'               => $snapshot['quantity'],
                    'price_cents'            => $snapshot['unit_price_cents'],
                    'discount_price_cents'   => $discountPriceCents,
                    'paid_price_cents'       => $paidPriceCents,
                    'payment_transaction_id' => $session->payment_data['intent']['payment_transaction_id'][$bagItemId] ?? null,
                    'status'                 => 'confirmed',
                    'payment_status'         => 'paid',
                ])
            );
        }

        return $items;
    }
}
