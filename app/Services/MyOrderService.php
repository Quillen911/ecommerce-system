<?php

namespace App\Services;

use App\Models\Order;
use App\Services\Campaigns\CampaignManager\CampaignManager;
use App\Services\Payments\IyzicoPaymentService;
use App\Models\Product;
use App\Models\Campaign;



class MyOrderService
{
    private $iyzicoService;
    
    public function __construct(IyzicoPaymentService $iyzicoService)
    {
        $this->iyzicoService = $iyzicoService;
    }
    
    public function getOrdersforUser($userId)
    {
        return Order::with('orderItems.product.category')
            ->where('user_id', $userId)
            ->orderByDesc('id')
            ->get();
    }

    public function getOneOrderforUser($userId, $orderId)
    {
        return Order::with('orderItems.product.category')
            ->where('id', $orderId)
            ->where('user_id', $userId)
            ->first();
    }
    


    public function refundSelectedItems($userId, $orderId, array $refundQuantitiesByItemId, CampaignManager $campaignManager){
        $order = Order::where('id', $orderId)
            ->where('user_id', $userId)
            ->first();
        if(!$order){
            return ['success' => false, 'error' => 'Sipariş bulunamadı.'];
        }
        if($order->payment_status === 'refunded' || $order->payment_status === 'canceled'){
            return ['success' => false, 'error' => 'Sipariş iade edilemez.', 'message' => 'Bu Sipariş iade veya iptal edildi.'];
        }
        $items = $order->orderItems()->whereIn('id', array_keys($refundQuantitiesByItemId))->get();
        
        if($items->isEmpty()){
            return ['success' => false, 'error' => 'İade edilecek ürün seçiniz.'];
        }

        $errors = [];
        $refundedCount = 0;
        foreach($items as $item){

            if (in_array($item->payment_status, ['refunded','canceled'])) { continue; }
            if(!$item->payment_transaction_id) { $errors[] = "TxId yok: item {$item->id}"; continue; }

            $paidPrice = $item->paid_price ?? 0;
            $refundedPrice = $item->refunded_price ?? 0;
            $remainingRefundedPrice = max(0, $paidPrice - $refundedPrice);
            if ($remainingRefundedPrice <= 0) { continue; }

            $requestedQty = ($refundQuantitiesByItemId[$item->id] ?? 0);
            if ($requestedQty <= 0) { continue; }

            $unitPaidPrice = $paidPrice / $item->quantity;
            if ($unitPaidPrice <= 0) { continue; }

            $maxItemsByPrice = floor($remainingRefundedPrice / $unitPaidPrice);
            $itemsToRefund = min($requestedQty, $maxItemsByPrice);
            if ($itemsToRefund <= 0) { continue; }

            $priceToRefund = ($itemsToRefund * $unitPaidPrice);
            if ($priceToRefund <= 0) { continue; }
            
            $refund = $this->iyzicoService->refundPayment($item->payment_transaction_id, $priceToRefund);

            if($refund['success']){
                $newRefunded = $refundedPrice + $priceToRefund;
                $fullyRefunded = $newRefunded == $paidPrice;

                if ($itemsToRefund > 0) {
                    Product::whereKey($item->product_id)->increment('stock_quantity', $itemsToRefund);
                    Product::whereKey($item->product_id)->decrement('sold_quantity', $itemsToRefund);
                }
                $item->update([
                    'status' => 'Müşteri İade Etti',
                    'payment_status' => $fullyRefunded ? 'refunded' : 'paid',
                    'refunded_price' => $newRefunded,
                    'refunded_at' => now(),
                ]);
                $refundedCount++;
            } else {
                $errors[] = ($refund['error'] ?? 'Bilinmeyen hata');
            }
        }
        $total = $order->orderItems()->count();
        $totalRefunded = $order->orderItems()->where('payment_status','refunded')->count();

        if ($total > 0 && $totalRefunded === $total) {
            if ($order->campaign_id && ($campaign = Campaign::find($order->campaign_id))) {
                $campaignManager->decreaseUserUsageCount($campaign);
                $campaignManager->increaseUsageLimit($campaign);
            }
            $order->update([
                'payment_status' => 'refunded',
                'status' => 'İade Edildi',
                'refunded_at' => now()
            ]);
            return ['success' => true, 'message' => 'Siparişin tamamı iade edildi.'];
        }
    
        if ($refundedCount > 0) {
            $order->update([
                'payment_status' => 'partial_refunded',
                'status' => 'Kısmi İade',
                'refunded_at' => now()
            ]);
            return ['success' => true, 'message' => 'Seçilen ürünler için kısmi iade yapıldı.'];
        }
        return ['success' => false, 'error' => implode(' | ', $errors) ?: 'İade yapılamadı'];
    }

}