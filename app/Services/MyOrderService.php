<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Cache;
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

        $result = $this->cancelOrRefund($order, $campaignManager);
        Cache::flush();
        return $result;
        
    }

    public function cancelOrRefund(Order $order, CampaignManager $campaignManager): array
    {
        $iyzicoService = new IyzicoPaymentService();
        $cancel = $iyzicoService->cancelPayment($order->payment_id);

        if($cancel['success']){
            foreach($order->orderItems as $item){
                Product::whereKey($item->product_id)->increment('stock_quantity', (int) $item->quantity);
                $item->update(['payment_status' => 'canceled',]);
            }
            $campaign = Campaign::where('id', $order->campaign_id)->first();
            if($campaign){
                $campaignManager->decreaseUserUsageCount($campaign);
                $campaignManager->increaseUsageLimit($campaign);
            }
            $order->update(['payment_status' => 'canceled', 'status' => 'Ödeme İptal Edildi']);
            return ['success' => true, 'message' => 'Ödeme iptal edildi'];
        }
        $errors = [];
        $refundedCount = 0;
        foreach($order->orderItems as $item){
            $txId = $item->payment_transaction_id;
            if(!$txId) { $errors[] = "TxId yok: item {$item->id}"; continue; }

            $amount = round($item->price * $item->quantity, 2);
            $refund = $iyzicoService->refundPayment($txId, $amount);

            if($refund['success']){
                Product::whereKey($item->product_id)->increment('stock_quantity', (int) $item->quantity);
                $item->update([
                    'payment_status' => 'refunded',
                    'refunded_amount' => $item->refunded_amount + $amount,
                    'refunded_at' => now(),
                ]);
                $refundedCount++;
            } else {
                $errors[] = $refund['error'] ?? "İade başarısız (item {$item->id})";
            }
        }

        if ($refundedCount === $order->orderItems()->count()) {
            $order->update([
                'payment_status' => 'refunded',
                'status' => 'İade Edildi',
            ]);
            return ['success' => true, 'message' => 'Siparişin tamamı iade edildi.'];
        }
        if ($refundedCount > 0) {
            $order->update([
                'payment_status' => 'partial_refunded',
                'status' => 'Kısmi İade',
            ]);
            return ['success' => true, 'message' => 'Kısmi iade yapıldı.'];
        }

        return ['success' => false, 'error' => implode(' | ', $errors) ?: 'İptal/iade yapılamadı'];
    }
}