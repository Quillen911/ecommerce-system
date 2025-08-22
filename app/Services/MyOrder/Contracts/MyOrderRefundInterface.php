<?php

namespace App\Services\MyOrder\Contracts;

use App\Services\Campaigns\CampaignManager;

interface MyOrderRefundInterface
{
    public function refundSelectedItems($userId, $orderId, array $refundQuantitiesByItemId, CampaignManager $campaignManager): array;
}