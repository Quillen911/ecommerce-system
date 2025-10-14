<?php

namespace App\Services\Checkout\Orders;

use App\Models\CheckoutSession;
use App\Models\User;
use App\Models\Order;

use App\Repositories\Contracts\Order\OrderRepositoryInterface;

class OrderFactory
{
    public function __construct(
        private readonly OrderRepositoryInterface $orders,
    ) {}

    public function create(User $user, CheckoutSession $session): Order
    {
        $totals   = $session->bag_snapshot['totals'] ?? [];
        $shipping = $session->shipping_data ?? [];
        $billing  = $session->billing_data ?? [];
        $appliedCampaign = $session->bag_snapshot['applied_campaign'] ?? null;

        return $this->orders->create([
            'user_id'                  => $user->id,
            'bag_id'                   => $session->bag_id,
            'user_shipping_address_id' => $shipping['shipping_address_id'] ?? null,
            'user_billing_address_id'  => $billing['billing_address_id'] ?? null,
            'campaign_id'              => $appliedCampaign['id'] ?? null,
            'campaign_info'            => $appliedCampaign['name'] ?? null,
            'order_number'             => $this->generateOrderNumber(),
            'subtotal_cents'           => $totals['total_cents'] ?? 0,
            'cargo_price_cents'        => $totals['cargo_cents'] ?? 0,
            'grand_total_cents'        => $totals['final_cents'] ?? 0,
            'currency'                 => 'TRY',
            'status'                   => 'confirmed',
        ]);
    }

    private function generateOrderNumber(): string
    {
        $lastOrder = $this->orders->latest();
        $lastNumber = $lastOrder?->order_number;

        $next = $lastNumber ? (int) $lastNumber + 1 : 1;

        return str_pad((string) $next, 8, '0', STR_PAD_LEFT);
    }
}

