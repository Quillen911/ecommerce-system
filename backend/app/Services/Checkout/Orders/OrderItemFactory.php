<?php

namespace App\Services\Checkout\Orders;

use App\Models\CheckoutSession;
use App\Models\Order;
use App\Repositories\Contracts\OrderItem\OrderItemRepositoryInterface;
use App\Repositories\Contracts\Product\ProductRepositoryInterface;
use Illuminate\Support\Collection;

class OrderItemFactory
{
    public function __construct(
        private readonly OrderItemRepositoryInterface $orderItems,
        private readonly ProductRepositoryInterface $productRepository,
    ) {}

    public function createMany(Order $order, CheckoutSession $session): Collection
    {
        $items = collect();

        $snapshot   = $session->bag_snapshot ?? [];
        $bagTotals  = $snapshot['totals'] ?? [];
        $bagItems   = $snapshot['items'] ?? [];

        $itemTotals       = $bagTotals['item_final_price_cents'] ?? [];
        $cargoShareTotals = $bagTotals['per_item_cargo_price_cents'] ?? [];

        $discountItems = collect($snapshot['applied_campaign']['discount_items'] ?? [])
            ->mapWithKeys(function ($entry, $key) {
                $bagItemId = $entry['bag_item_id'] ?? (is_numeric($key) ? (int) $key : null);
                if ($bagItemId === null) {
                    return [];
                }
                return [$bagItemId => (int) ($entry['discount_cents'] ?? 0)];
            });
        
        foreach ($bagItems as $snapshot) {
            $bagItemId = $snapshot['bag_item_id'];
            $quantity = $snapshot['quantity'];
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
                    'quantity'               => $quantity,
                    'price_cents'            => $snapshot['unit_price_cents'],
                    'discount_price_cents'   => $discountItems->get($bagItemId, 0),
                    'paid_price_cents'       => (int) ($itemTotals[$bagItemId] ?? 0),
                    'cargo_share_cents'      => (int) ($cargoShareTotals[$bagItemId] ?? 0),
                    'payment_transaction_id' => $session->payment_data['intent']['payment_transaction_id'][$bagItemId] ?? null,
                    'status'                 => 'confirmed',
                    'payment_status'         => 'paid',
                ])
            );
            $this->productRepository->incrementSoldQuantity($snapshot['product_id'], $quantity);
        }

        return $items;
    }
}
