<?php

namespace App\Services\Seller;

use App\Services\Payments\IyzicoPaymentService;
use App\Repositories\Contracts\OrderItem\OrderItemRepositoryInterface;
use App\Repositories\Contracts\Product\ProductRepositoryInterface;
use App\Repositories\Contracts\Store\StoreRepositoryInterface;

class SellerOrderService
{
    protected $iyzicoService;
    protected $orderItemRepository;
    protected $productRepository;
    protected $storeRepository;
    public function __construct(
        IyzicoPaymentService $iyzicoService, 
        OrderItemRepositoryInterface $orderItemRepository,
        ProductRepositoryInterface $productRepository,
        StoreRepositoryInterface $storeRepository
    ) {
        $this->iyzicoService = $iyzicoService;
        $this->orderItemRepository = $orderItemRepository;
        $this->productRepository = $productRepository;
        $this->storeRepository = $storeRepository;
    }

    public function getSellerOrders($sellerId)
    {
        $store = $this->storeRepository->getStoreBySellerId($sellerId);
        if(!$store){
            throw new \Exception('Mağaza bulunamadı');
        }
        return $this->orderItemRepository->getOrderItemsBySeller($store->id);
    }

    public function getSellerOneOrder($sellerId, $id)
    {
        $store = $this->storeRepository->getStoreBySellerId($sellerId);
        if(!$store){
            throw new \Exception('Mağaza bulunamadı');
        }
        return $this->orderItemRepository->getOrderItemBySeller($store->id, $id);
    }

    public function confirmItem($sellerId, $id)
    {
        $store = $this->storeRepository->getStoreBySellerId($sellerId);
        if(!$store){
            throw new \Exception('Mağaza bulunamadı');
        }
        $orderItem = $this->orderItemRepository->getOrderItemById($store->id, $id);
        if (!$orderItem) {
            throw new \Exception('Sipariş bulunamadı');
        }
        if($orderItem->status === 'refunded'){
            throw new \Exception('Sipariş iade edildi');
        }
        if($orderItem->status !== 'confirmed'){
            throw new \Exception('Sipariş durumu uygun değil');
        }
        
        $orderItem->status = 'shipped';
        $orderItem->save();

        return $orderItem;
    }

    public function refundSelectedItems($sellerId, $id)
    {
        $store = $this->storeRepository->getStoreBySellerId($sellerId);
        if(!$store){
            throw new \Exception('Mağaza bulunamadı');
        }
        $orderItem = $this->orderItemRepository->getOrderItemById($store->id, $id);
        if (!$orderItem) {
            throw new \Exception('Sipariş bulunamadı');
        }
        
        $refundItem = $this->iyzicoService->refundPayment($orderItem->payment_transaction_id, $orderItem->paid_price);
        
        if($refundItem['success']){
            $this->productRepository->incrementStockQuantity($orderItem->product_id, $orderItem->quantity);

            $orderItem->status = 'refunded';
            $orderItem->payment_status = 'refunded';
            $orderItem->refunded_at = now();
            $orderItem->save();

            $order = $orderItem->order;
            $this->updateOrderStatusAfterRefund($order);
            return ['success' => true, 'message' => 'Sipariş başarıyla iade edildi', 'orderItem' => $orderItem];
        }
        throw new \Exception('Sipariş iade edilirken hata oluştu');
    }

    private function updateOrderStatusAfterRefund($order)
    {
        $orderItems = $order->orderItems;

        $totalItems = $orderItems->count();
        $completedItems = $orderItems->where('status', 'shipped')->count();
        $refundedItems = $orderItems->where('payment_status', 'refunded')->count();
        $confirmedItems = $orderItems->where('status', 'confirmed')->count();

        if ($refundedItems === $totalItems) {
            $order->status = 'İade Edildi'; 
            $order->payment_status = 'refunded';
            $order->refunded_at = now();
            
        } elseif ($refundedItems > 0 && ($completedItems > 0 || $confirmedItems > 0)) {
            $order->status = 'Kısmi İade'; 
            $order->payment_status = 'partial_refunded';
        }
        
        $order->save();
    }

}