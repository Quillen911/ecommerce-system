<?php

namespace App\Services;

use App\Models\Order;
use App\Services\Campaigns\CampaignManager\CampaignManager;
use App\Services\Payments\IyzicoPaymentService;
use App\Models\Product;
use App\Models\Campaign;

class MyOrderService
{
    public function getOrdersforUser($userId)
    {
        return Order::with('orderItems.product.category')
                        ->where('Bag_User_id' , $userId)
                        ->orderByDesc('id')
                        ->get();
    }

    public function getOneOrderforUser($userId, $orderId)
    {
        return Order::with('orderItems.product.category')
                        ->where('Bag_User_id',$userId)
                        ->where('id', $orderId)
                        ->first();
    }
    
    public function cancelOrder($userId, $orderId, $campaignManager)
    {
        $order = Order::where('Bag_User_id', $userId)
                        ->where('id', $orderId)
                        ->first();

        if(!$order){
            return null;
        }

        $iyzicoService = new IyzicoPaymentService();
        $campaign = Campaign::where('id', $order->campaign_id)->first();

        $cancel = $iyzicoService->cancelPayment((string) $order->payment_id);
        if ($cancel['success'] ?? false) {
            foreach($order->orderItems as $item){
                Product::whereKey($item->product_id)->increment('stock_quantity', (int) $item->quantity);
                $item->update([
                    'payment_status' => 'canceled',
                    'canceled_at' => now(),
                ]);
            }
            if($campaign){
                $campaignManager->decreaseUserUsageCount($campaign);
                $campaignManager->increaseUsageLimit($campaign);
            }
            $order->update([
                'payment_status' => 'canceled',
                'status' => 'Ödeme İptal Edildi',
                'canceled_at' => now(),
            ]);
            return ['success' => true, 'message' => 'Siparişin tamamı iptal edildi.'];
        }

        return ['success' => false, 'error' => $cancel['error'] ?? 'Ödeme iptal edilemedi.'];
    }

    public function refundSelectedItems($userId, $orderId, array $itemIds, CampaignManager $campaignManager){
        $order = Order::where('Bag_User_id', $userId)
                        ->where('id', $orderId)
                        ->first();
        if(!$order){
            return ['success' => false, 'error' => 'Sipariş bulunamadı.'];
        }
        $iyzicoService = new IyzicoPaymentService();
        $items = $order->orderItems()->whereIn('id', $itemIds)->get();

        
        $allOrderItems = $order->orderItems()->get();
        $orderBasketTotal = round($items->sum(function($it){ return ((float)$it->price) * (int)$it->quantity; }), 2);
        $orderPaidAmount = round((float) ($order->paid_price ?? $order->campaing_price), 2);
        $proportionalFactor = $orderBasketTotal > 0 ? min(1.0, round($orderPaidAmount / $orderBasketTotal, 6)) : 1.0;
        
        if($items->isEmpty()){
            return ['success' => false, 'error' => 'İade edilecek ürün seçiniz.'];
        }

        $errors = [];
        $refundedCount = 0;
        foreach($items as $item){
            if (in_array($item->payment_status, ['refunded','canceled'])) { continue; }
            if(!$item->payment_transaction_id) { $errors[] = "TxId yok: item {$item->id}"; continue; }

            $lineTotal = round(((float) $item->price) * (int) $item->quantity, 2);
            $alreadyRefunded = round((float) $item->refunded_amount, 2);
            $maxRefundableForItem = round($lineTotal * $proportionalFactor, 2);
            $remainingRefundable = max(0, round($maxRefundableForItem - $alreadyRefunded, 2));

            if ($remainingRefundable <= 0) { continue; }

            $refund = $iyzicoService->refundPayment($item->payment_transaction_id, $remainingRefundable);

            if($refund['success']){
                $newRefundedAmount = round($alreadyRefunded + $remainingRefundable, 2);
                // Tam iade: iade edilen tutar, bu kalem için azami iade edilebilir tutara ulaştı mı?
                $isFullyRefunded = $newRefundedAmount + 0.00001 >= $maxRefundableForItem;

                if ($isFullyRefunded) {
                    Product::whereKey($item->product_id)->increment('stock_quantity', (int) $item->quantity);
                }
                $item->update([
                    'payment_status' => $isFullyRefunded ? 'refunded' : 'paid',
                    'refunded_amount' => $newRefundedAmount,
                    'refunded_at' => now(),
                ]);
                $refundedCount++;
            } else {
                $errors[] = ($refund['error'] ?? 'Bilinmeyen hata') . " - İade başarısız (item {$item->id})";
            }
        }
        $total = $order->orderItems()->count();
        $totalRefunded = $order->orderItems()->where('payment_status','refunded')->count();

        if ($totalRefunded === $total && $total > 0 ) {
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