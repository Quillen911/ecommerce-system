<?php
namespace App\Services\Order\Contracts;

use App\Models\User;
use App\Models\Order;
use App\Services\Campaigns\CampaignManager;

interface OrderCreationInterface
{
    public function createOrderRecord(User $user, int $selectedCreditCard, array $orderData, $selectedShippingAddress, $selectedBillingAddress): Order;
    public function createOrderItems(Order $order, $products, $eligible_products, $perProductDiscount): void;
    public function applyCampaign(int $campaignId, CampaignManager $campaignManager): void;
    public function getOrder(int $userId, int $orderId): ?Order;
}