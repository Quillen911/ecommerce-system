<?php

namespace App\Services\MyOrder\Services;

use App\Services\MyOrder\Contracts\MyOrderUpdateInterface;
use App\Services\Campaigns\CampaignManager\CampaignManager;
use App\Models\Product;
use App\Models\Campaign;

class MyOrderUpdateService implements MyOrderUpdateInterface
{
    public function updateOrderItem($item, $refundedAmount, $refundedQuantity): void
    {
        $newRefunded = ($item->refunded_price ?? 0) + $refundedAmount;
        $fullyRefunded = $newRefunded >= $item->paid_price;

        $item->update([
            'status' => 'Müşteri İade Etti',
            'payment_status' => $fullyRefunded ? 'refunded' : 'paid',
            'refunded_price' => $newRefunded,
            'refunded_at' => now(),
        ]);
    }

    public function updateProductStock($productId, $refundedQuantity): void
    {
        if($refundedQuantity > 0){
            Product::whereKey($productId)->increment('stock_quantity', $refundedQuantity);
            Product::whereKey($productId)->decrement('sold_quantity', $refundedQuantity);
        }
    }

    public function updateOrderStatus($order, $refundResults, $campaignManager): array
    {
        $successfulRefunds = array_filter($refundResults, fn($r) => $r['success']);
        
        if (empty($successfulRefunds)) {
            $errors = array_column($refundResults, 'error');
            return ['success' => false, 'error' => implode(' | ', $errors)];
        }

        $totalItems = $order->orderItems()->count();
        $totalRefunded = $order->orderItems()->where('payment_status', 'refunded')->count();

        if ($totalRefunded === $totalItems) {
            $this->handleFullRefund($order, $campaignManager);
            return ['success' => true, 'message' => 'Siparişin tamamı iade edildi.'];
        } else {
            $order->update([
                'payment_status' => 'partial_refunded',
                'status' => 'Kısmi İade',
                'refunded_at' => now()
            ]);
            return ['success' => true, 'message' => 'Seçilen ürünler için kısmi iade yapıldı.'];
        }
    }
    private function handleFullRefund($order, $campaignManager): void
    {
        if ($order->campaign_id && ($campaign = Campaign::find($order->campaign_id))) {
            $campaignManager->decreaseUserUsageCount($campaign);
            $campaignManager->increaseUsageLimit($campaign);
        }

        $order->update([
            'payment_status' => 'refunded',
            'status' => 'İade Edildi',
            'refunded_at' => now()
        ]);
    }
}