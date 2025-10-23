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

        $bagSnapshot       = $session->bag_snapshot ?? [];
        $bagItems          = $bagSnapshot['items'] ?? [];
        $cargoPriceCents   = $bagSnapshot['totals']['cargo_cents'] ?? 0;

        $discounts = collect($bagSnapshot['applied_campaign']['discount_items'] ?? [])
            ->keyBy('bag_item_id');

        $productTotals = collect($bagItems)->mapWithKeys(function ($snapshot) use ($discounts) {
            $bagItemId    = $snapshot['bag_item_id'];
            $discount     = $discounts->get($bagItemId);
            $productTotal = $discount['discounted_total_cents'] ?? $snapshot['total_price_cents'];

            return [$bagItemId => (int) $productTotal];
        });

        $productTotalSum = max($productTotals->sum(), 1);
        $allocatedCargo  = 0;

        foreach ($bagItems as $index => $snapshot) {
            $bagItemId = $snapshot['bag_item_id'];
            $discount  = $discounts->get($bagItemId);

            $paidPriceCents     = $discount['discounted_total_cents'] ?? $snapshot['total_price_cents'];
            $discountPriceCents = $discount['discount_cents'] ?? 0;

            $cargoShareCents = $index === array_key_last($bagItems)
                ? $cargoPriceCents - $allocatedCargo
                : (int) round($cargoPriceCents * ($productTotals[$bagItemId] / $productTotalSum));

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
                    'paid_price_cents'       => $paidPriceCents + $cargoShareCents,
                    'cargo_share_cents'      => $cargoShareCents,
                    'payment_transaction_id' => $session->payment_data['intent']['payment_transaction_id'][$bagItemId] ?? null,
                    'status'                 => 'confirmed',
                    'payment_status'         => 'paid',
                ])
            );

            $allocatedCargo += $cargoShareCents;
        }

        return $items;
    }
}
