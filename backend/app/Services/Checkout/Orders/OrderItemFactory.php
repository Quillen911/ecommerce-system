<?php

namespace App\Services\Checkout\Orders;

use App\Models\CheckoutSession;
use App\Models\Order;
use App\Repositories\Contracts\OrderItem\OrderItemRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class OrderItemFactory
{
    public function __construct(
        private readonly OrderItemRepositoryInterface $orderItems,
    ) {}

    public function createMany(Order $order, CheckoutSession $session): Collection
    {
        $items = collect();

        foreach ($session->bag_snapshot['items'] as $snapshot) {
            $items->push(
                $this->orderItems->create([
                    'order_id'               => $order->id,
                    'product_id'             => $snapshot['product_id'],
                    'variant_size_id'        => $snapshot['variant_size_id'],
                    'store_id'               => $snapshot['store_id'] ?? null,
                    'product_title'          => $snapshot['product_title'],
                    'quantity'               => $snapshot['quantity'],
                    'price_cents'            => $snapshot['unit_price_cents'],
                    'paid_price_cents'       => $snapshot['total_price_cents'],
                    'payment_transaction_id' => $session->payment_data['intent']['payment_transaction_id'][$snapshot['bag_item_id']] ?? null,
                    'status'                 => 'confirmed',
                    'payment_status'         => 'paid',
                    Log::info('Order item created', [
                    'order_id'               => $order->id,
                    'product_id'             => $snapshot['product_id'],
                    'variant_size_id'        => $snapshot['variant_size_id'],
                    'store_id'               => $snapshot['store_id'] ?? null,
                    'product_title'          => $snapshot['product_title'],
                    'quantity'               => $snapshot['quantity'],
                    'price_cents'            => $snapshot['unit_price_cents'],
                    'paid_price_cents'       => $snapshot['total_price_cents'],
                    'payment_transaction_id' => $session->payment_data['intent']['payment_transaction_id'][$snapshot['bag_item_id']] ?? null,
                    'status'                 => 'confirmed',
                    'payment_status'         => 'paid',
                    ]),
                ])
                
            );
        }

        return $items;
    }
}
