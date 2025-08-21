<?php

namespace App\Services\MyOrder\Services;

use App\Services\MyOrder\Contracts\MyOrderCheckInterface;
use App\Repositories\Contracts\Order\OrderRepositoryInterface;
class MyOrderCheckService implements MyOrderCheckInterface
{
    protected $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }
    public function checkOrder($userId, $orderId): array
    {
        $order = $this->orderRepository->getUserOrderById($userId, $orderId);

        if(!$order){
            return ['success' => false, 'error' => 'Sipariş bulunamadı.'];
        }

        if($order->payment_status === 'refunded' || $order->payment_status === 'canceled'){
            return ['success' => false, 'error' => 'Sipariş iade edilemez.', 'message' => 'Bu Sipariş iade veya iptal edildi.'];
        }
        return ['success' => true, 'order' => $order];
    }

    public function checkItems($order, array $refundQuantitiesByItemId): array
    {
        $items = $order->orderItems()->whereIn('id', array_keys($refundQuantitiesByItemId))->get();
        if($items->isEmpty()){
            return ['success' => false, 'error' => 'İade edilecek ürün seçiniz.'];
        }
        return ['success' => true, 'items' => $items];
    }
}