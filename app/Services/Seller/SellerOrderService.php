<?php

namespace App\Services\Seller;

use App\Services\Payments\IyzicoPaymentService;
use App\Repositories\Contracts\OrderItem\OrderItemRepositoryInterface;
use App\Repositories\Contracts\Product\ProductRepositoryInterface;
use App\Repositories\Contracts\Store\StoreRepositoryInterface;
use App\Services\Shipping\Contracts\ShippingServiceInterface;
use App\Jobs\ShippedOrderItemNotification;
use App\Jobs\RefundOrderItemNotification;

class SellerOrderService
{
    protected $iyzicoService;
    protected $orderItemRepository;
    protected $productRepository;
    protected $storeRepository;
    protected $shippingService;
    public function __construct(
        IyzicoPaymentService $iyzicoService, 
        OrderItemRepositoryInterface $orderItemRepository,
        ProductRepositoryInterface $productRepository,
        StoreRepositoryInterface $storeRepository,
        ShippingServiceInterface $shippingService
    ) {
        $this->iyzicoService = $iyzicoService;
        $this->orderItemRepository = $orderItemRepository;
        $this->productRepository = $productRepository;
        $this->storeRepository = $storeRepository;
        $this->shippingService = $shippingService;
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
        if ($orderItem->shippingItem) {
            throw new \Exception('Bu ürün için zaten kargo oluşturulmuş.');
        }

        $order = $orderItem->order;
        $user = $order->user;

        $payload = [
            'order_item_id' => $orderItem->id,
            'username' => $user->username,
            'phone' => $user->phone,
            'email' => $user->email,
            'address' => $user->address,
            'city' => $user->city,
            'district' => $user->district,
            'product_title' => $orderItem->product_title,
            'quantity' => $orderItem->quantity,
        ];

        $result = $this->shippingService->createShipment($payload);

        if(!($result['success'])){
            throw new \Exception('Kargo oluşturulamadı: '.($result['error'] ?? 'bilinmeyen hata'));
        }
        $orderItem->status = 'shipped';
        $orderItem->save();

        $shippingItem = $this->createShippingItem($orderItem, $result);

        ShippedOrderItemNotification::dispatch($orderItem, $user)->onQueue('notifications');

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
            $orderItem->update([
                'status' => 'Satıcı İade Etti',
                'payment_status' => 'refunded',
                'refunded_price_cents' => $orderItem->paid_price_cents,
                'refunded_price' => $orderItem->paid_price,
                'refunded_quantity' => $orderItem->quantity,
                'refunded_at' => now(),
            ]);

            $order = $orderItem->order;
            $user = $order->user;
            $this->updateOrderStatusAfterRefund($order);
            RefundOrderItemNotification::dispatch($orderItem, $user, $orderItem->quantity, $orderItem->paid_price)->onQueue('notifications');
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

    private function createShippingItem($orderItem, $result)
    {
        $shippingItem = $orderItem->shippingItem()->create([
            'order_item_id' => $orderItem->id,
            'tracking_number' => $result['tracking_number'] ?? null,
            'shipping_company' => $result['shipping_company'] ?? null,
            'shipping_status' => $result['shipping_status'] ?? 'pending',
            'estimated_delivery_date' => $result['estimated_delivery_date'] ?? null,
            'shipping_notes' => $result['shipping_notes'] ?? null,
        ]);
        return $shippingItem;
    }
}