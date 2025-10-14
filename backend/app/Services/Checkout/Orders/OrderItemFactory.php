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
        $paidPrice = $session->bag_snapshot['totals']['final_cents'];
        $discountsByBagItem = collect($session->bag_snapshot['applied_campaign']['discount_items'] ?? [])
            ->keyBy('discount_item_id');
            
        foreach ($session->bag_snapshot['items'] as $snapshot) {
            $discount = $discountsByBagItem[$snapshot['bag_item_id']] ?? null;
            $items->push(
                $this->orderItems->create([
                    'order_id'               => $order->id,
                    'product_id'             => $snapshot['product_id'],
                    'variant_size_id'        => $snapshot['variant_size_id'],
                    'store_id'               => $snapshot['store_id'] ?? null,
                    'product_title'          => $snapshot['product_title'],
                    'size_name'              => $snapshot['size_name'],
                    'color_name'             => $snapshot['color_name'],
                    'quantity'               => $snapshot['quantity'],
                    'price_cents'            => $snapshot['unit_price_cents'],
                    'discount_price_cents'   => $discount['discount_cents'] ?? 0,
                    'paid_price_cents'       => $paidPrice,
                    'payment_transaction_id' => $session->payment_data['intent']['payment_transaction_id'][$snapshot['bag_item_id']] ?? null,
                    'status'                 => 'confirmed',
                    'payment_status'         => 'paid',
                ])
                
            );
        }

        return $items;
    }
}
