<?php

namespace App\Services\Seller;

use App\Services\Payments\IyzicoPaymentService;
use App\Repositories\Contracts\OrderItem\OrderItemRepositoryInterface;
use App\Repositories\Contracts\Product\ProductRepositoryInterface;
use App\Repositories\Contracts\Store\StoreRepositoryInterface;
use App\Repositories\Contracts\AuthenticationRepositoryInterface;
use App\Services\Shipping\Contracts\ShippingServiceInterface;
use App\Jobs\ShippedOrderItemNotification;
use App\Jobs\RefundOrderItemNotification;
use App\Models\Payment;
use App\Models\PaymentProvider;
use App\Services\Payments\Contracts\PaymentGatewayInterface;
use App\Services\Order\Contracts\Refund\OrderCalculationInterface;
use App\Services\Order\Contracts\Refund\OrderCheckInterface;
use App\Services\Order\Contracts\Refund\OrderUpdateInterface;
use App\Services\Order\Services\Refund\RefundPlacementService;
use App\Models\OrderRefund;
use App\Models\OrderRefundItem;
use Illuminate\Support\Facades\DB;

class SellerOrderService
{
    
    public function __construct(
       private readonly IyzicoPaymentService $iyzicoService, 
       private readonly OrderItemRepositoryInterface $orderItemRepository,
       private readonly ProductRepositoryInterface $productRepository,
       private readonly StoreRepositoryInterface $storeRepository,
       private readonly ShippingServiceInterface $shippingService,
       private readonly AuthenticationRepositoryInterface $authenticationRepository,
       private readonly OrderCalculationInterface $orderCalculationService,
       private readonly OrderCheckInterface $orderCheckService,
       private readonly OrderUpdateInterface $orderUpdateService,
       private readonly RefundPlacementService $refundPlacementService,
    ) {
    
    }

    public function getSellerOrders()
    {
        $seller = $this->authenticationRepository->getSeller();
        if(!$seller){
            throw new \Exception('Satıcı bulunamadı');
        }
        $store = $this->storeRepository->getStoreBySellerId($seller->id);
        if(!$store){
            throw new \Exception('Mağaza bulunamadı');
        }
        return $this->orderItemRepository->getOrderItemsBySeller($store->id);
    }

    public function getSellerOneOrder($id)
    {
        $seller = $this->authenticationRepository->getSeller();
        if(!$seller){
            throw new \Exception('Satıcı bulunamadı');
        }
        $store = $this->storeRepository->getStoreBySellerId($seller->id);
        if(!$store){
            throw new \Exception('Mağaza bulunamadı');
        }
        return $this->orderItemRepository->getOrderItemBySeller($store->id, $id);
    }

    public function confirmItem($id)
    {
        $seller = $this->authenticationRepository->getSeller();
        if(!$seller){
            throw new \Exception('Satıcı bulunamadı');
        }
        $store = $this->storeRepository->getStoreBySellerId($seller->id);
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

        ShippedOrderItemNotification::dispatch($orderItem, $user)->onQueue('notifications');

        return $orderItem;
    }

    public function refundSelectedItems($id, array $payload)
    {
        $seller = $this->authenticationRepository->getSeller();
        if(!$seller){
            throw new \Exception('Satıcı bulunamadı');
        }
        $store = $this->storeRepository->getStoreBySellerId($seller->id);
        if(!$store){
            throw new \Exception('Mağaza bulunamadı');
        }
        $orderItem = $this->orderItemRepository->getOrderItemById($store->id, $id);
        if (!$orderItem) {
            throw new \Exception('Sipariş bulunamadı');
        }
        $blockedStatuses = ['refunded', 'canceled'];
        $allowedStatuses = ['confirmed', 'partial_refunded'];

        if (in_array($orderItem->status, $blockedStatuses, true)
            || !in_array($orderItem->status, $allowedStatuses, true)) {
            throw new \Exception('Bu ürün iade edilemez veya iade edilmiş.');
        }
        $result = $this->processRefund($orderItem, $payload);
        return $result;
        
    }

    private function processRefund($orderItem, array $payload)
    {
        $order = $orderItem->order;
        $payment = Payment::where('order_id', $order->id)->firstOrFail();
        $provider = PaymentProvider::where('code', $payment->provider)->firstOrFail();
        $refundAmount = $this->calculateRefundPrice($orderItem, $payload);

        $gateway = app(PaymentGatewayInterface::class, ['provider' => $provider]);
        $gatewayResponse = $gateway->refundPayment(
            $orderItem->payment_transaction_id,
            $refundAmount,
            $payload
        );

        DB::transaction(function () use ($orderItem, $payload, $refundAmount) {
            $newRefundedQuantity = ($orderItem->refunded_quantity ?? 0) + (int) $payload['quantity'];
            $newRefundedPrice = ($orderItem->refunded_price_cents ?? 0) + $refundAmount;

            $orderRefund = OrderRefund::create([
                'order_id'           => $orderItem->order_id,
                'user_id'            => $payload['user_id'] ?? $orderItem->order->user_id,
                'refund_total_cents' => $refundAmount,
            ]);

            $orderItem->update([
                'refunded_quantity'    => $newRefundedQuantity,
                'refunded_price_cents' => $newRefundedPrice,
                'status'               => $newRefundedQuantity >= $orderItem->quantity ? 'refunded' : 'partial_refunded',
                'payment_status'       => $newRefundedQuantity >= $orderItem->quantity ? 'refunded' : 'partial_refunded',
                'refunded_at'          => now(),
            ]);

            OrderRefundItem::create([
                'order_refund_id'      => $orderRefund->id,
                'order_item_id'        => $orderItem->id,
                'quantity'             => (int) $payload['quantity'],
                'refund_amount_cents'  => $refundAmount,
                'reason'               => $payload['reason'],
                'byWho'                => 'seller',
                'inspection_status'    => 'pending',
                'inspection_note'      => null,
            ]);
        });

        return $gatewayResponse;
    }

    private function calculateRefundPrice($orderItem, $payload)
    {
        $perItemPrice = $orderItem->paid_price_cents / $orderItem->quantity;
        $remainingCents = max(0, $orderItem->paid_price_cents - $orderItem->refunded_price_cents);
        $availableQuantity = $orderItem->quantity - ($orderItem->refunded_quantity ?? 0);
        $refundPrice = $perItemPrice * $payload['quantity'];
        $refundPrice = round($refundPrice);

        if($availableQuantity == 1){
            $refundPrice = $remainingCents;
        }
        return $refundPrice;
    }

}