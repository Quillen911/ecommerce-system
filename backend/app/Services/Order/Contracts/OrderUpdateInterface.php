<?php

namespace App\Services\Order\Contracts;

use App\Services\Campaigns\CampaignManager;

interface OrderUpdateInterface
{
    public function updateOrderItem($item, int $refundedAmountCents, int $refundedQuantity): void;
    public function updateProductStock($productId, $refundedQuantity): void;
    public function updateOrderStatus($order, array $refundResults, CampaignManager $campaignManager): array;
}