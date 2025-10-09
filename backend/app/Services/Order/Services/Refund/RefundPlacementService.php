<?php

namespace App\Services\Order\Services\Refund;

use App\Services\Order\Services\Refund\OrderRefundFactory;
use App\Services\Order\Services\Refund\OrderRefundItemFactory;
use Illuminate\Support\Facades\DB;

use App\Models\Order;

class RefundPlacementService
{
    public function __construct(
        private OrderRefundFactory $orderRefundFactory,
        private OrderRefundItemFactory $orderRefundItemFactory,
    ) {}

    public function placeRefund(array $payload, array $items, Order $order)
    {
        return DB::transaction(function () use ($payload, $items, $order) {
            $refund = $this->orderRefundFactory->create($payload, $order);

            $this->orderRefundItemFactory->create($items, $refund->id);

            $refund->refresh()->load('items');

            return $refund;
        });
    }
}
