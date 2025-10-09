<?php

namespace App\Services\Order\Services\Refund;

use App\Repositories\Contracts\RefundOrder\RefundOrderItemRepositoryInterface;

class OrderRefundItemFactory
{
    public function __construct(
        private RefundOrderItemRepositoryInterface $orderRefundItemRepository
    ) {}

    public function create(array $items, int $orderRefundId): void
    {
        foreach ($items as $item) {
            if (! is_array($item) || empty($item['itemId'])) {
                continue;
            }
            if (!$item['canRefund']) {
                continue;
            }

            $this->orderRefundItemRepository->create([
                'order_refund_id'     => $orderRefundId,
                'order_item_id'       => $item['itemId'],
                'quantity'            => $item['requestedQty'] ?? 0,
                'refund_amount_cents' => $item['priceToRefundCents'] ?? 0,
                'inspection_status'   => 'pending',
                'inspection_note'     => null,
            ]);
        }
    }
}

