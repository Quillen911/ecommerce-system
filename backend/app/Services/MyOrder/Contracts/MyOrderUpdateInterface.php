<?php

namespace App\Services\MyOrder\Contracts;

use App\Services\Campaigns\CampaignManager;

interface MyOrderUpdateInterface
{
    public function updateOrderItem($item, int $refundedAmountCents, int $refundedQuantity): void;
    public function updateProductStock($productId, $refundedQuantity): void;
    public function updateOrderStatus($order, array $refundResults, CampaignManager $campaignManager): array;
}