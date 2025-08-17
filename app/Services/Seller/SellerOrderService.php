<?php

namespace App\Services\Seller;

use App\Models\Product;
use App\Models\OrderItem;
use App\Services\Payments\IyzicoPaymentService;

class SellerOrderService
{
    private $iyzicoService;
    public function __construct(IyzicoPaymentService $iyzicoService)
    {
        $this->iyzicoService = $iyzicoService;
    }

    public function getSellerOrders($store)
    {
        $orderItems = OrderItem::with('product')
            ->where('store_id', $store->id)
            ->orderBy('created_at', 'desc')
            ->get();
        return $orderItems;
    }

    public function getSellerOneOrder($store, $id)
    {
        $orderItem = OrderItem::with('product')->where('store_id', $store->id)->where('order_id', $id)->first();
        return $orderItem;
    }

    public function confirmItem($store, $id)
    {
        $orderItem = OrderItem::where('store_id', $store->id)->where('id', $id)->first();
        
        if (!$orderItem) {
            return null;
        }
        if($orderItem->status === 'refunded'){
            return ['success' => false, 'message' => 'Sipariş iade edildi'];
        }
        
        $orderItem->status = 'shipped';
        $orderItem->save();
        return $orderItem;  
    }

    public function refundSelectedItems($store, $id)
    {
        $orderItem = OrderItem::where('id', $id)->where('store_id', $store->id)->first();
        if (!$orderItem) {
            return ['success' => false, 'message' => 'Sipariş bulunamadı'];
        }
        
        $refundItem = $this->iyzicoService->refundPayment($orderItem->payment_transaction_id, $orderItem->paid_price);
        
        if($refundItem['success']){
            Product::whereKey($orderItem->product_id)->increment('stock_quantity', $orderItem->quantity);
            $orderItem->status = 'refunded';
            $orderItem->payment_status = 'refunded';
            $orderItem->refunded_at = now();
            $orderItem->save();


            $order = $orderItem->order;
            $this->updateOrderStatusAfterRefund($order);
            return ['success' => true, 'message' => 'Sipariş başarıyla iade edildi', 'orderItem' => $orderItem];
        }
        return ['success' => false, 'message' => 'Sipariş iade edilirken hata oluştu'];
    }

    private function updateOrderStatusAfterRefund($order)
    {
        $orderItems = $order->orderItems;

        $totalItems = $orderItems->count();
        $completedItems = $orderItems->where('status', 'shipped')->count();
        $refundedItems = $orderItems->where('payment_status', 'refunded')->count();
        $confirmedItems = $orderItems->where('status', 'confirmed')->count();

        if ($refundedItems === $totalItems) {
            $order->status = 'refunded';
            $order->payment_status = 'refunded';
            $order->refunded_at = now();
            
        } elseif ($refundedItems > 0 && ($completedItems > 0 || $confirmedItems > 0)) {
            $order->status = 'partial_refunded';
            $order->payment_status = 'partial_refunded';
        }
        
        $order->save();
    }

}