<?php

namespace App\Services\Order\Contracts;

use App\Services\Campaigns\CampaignManager;

interface OrderRefundInterface
{
    public function refundSelectedItems($orderId, array $refundQuantitiesByItemId, CampaignManager $campaignManager): array;
}