<?php

namespace App\Services\MyOrder\Services;

use App\Services\MyOrder\Contracts\MyOrderUpdateInterface;
use App\Services\Campaigns\CampaignManager\CampaignManager;
use App\Models\Campaign;
use App\Repositories\Contracts\Product\ProductRepositoryInterface;
class MyOrderUpdateService implements MyOrderUpdateInterface
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }
    public function updateOrderItem($item, $refundedAmount, $refundedQuantity): void
    {
        $newRefundedCents = ($item->refunded_price_cents ?? 0) + (int)($refundedAmount * 100);
        $fullyRefunded = $newRefundedCents >= $item->paid_price_cents;

        $item->update([
            'status' => 'Müşteri İade Etti',
            'payment_status' => $fullyRefunded ? 'refunded' : 'paid',
            'refunded_price_cents' => $newRefundedCents,
            'refunded_price' => $newRefundedCents / 100, // TL cinsinden güncelleme
            'refunded_quantity' => ($item->refunded_quantity ?? 0) + $refundedQuantity,
            'refunded_at' => now(),
        ]);
    }

    public function updateProductStock($productId, $refundedQuantity): void
    {
        if($refundedQuantity > 0){
            $this->productRepository->incrementStockQuantity($productId, $refundedQuantity);
            $this->productRepository->decrementSoldQuantity($productId, $refundedQuantity);
        }
    }

    public function updateOrderStatus($order, $refundResults, CampaignManager $campaignManager): array
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