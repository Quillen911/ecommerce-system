<?php

namespace App\Services\Order\Services\Refund;

use App\Services\Order\Contracts\Refund\OrderCheckInterface;
use App\Repositories\Contracts\Order\OrderRepositoryInterface;
use App\Traits\GetUser;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class OrderCheckService implements OrderCheckInterface
{
    
    public function __construct(
       private OrderRepositoryInterface $orderRepository,
    ) {
    }
    public function checkOrder($orderId, $userId)
    {
        $order = $this->orderRepository->getOrderForUser($orderId, $userId);

        if(!$order){
            throw new ModelNotFoundException('Sipariş bulunamadı.');
        }

        if($order->status === 'refunded' || $order->status === 'canceled' || $order->status !== 'confirmed'){
            throw new ModelNotFoundException('Bu Sipariş iade edilemez.');
        }
        return $order;
    }

    public function checkItems($order, array $payloadItems): array
    {
        $items = [];
        foreach ($payloadItems as $item) {
            $orderItemId = $item['order_item_id'];
            $checkItems = $order->orderItems()->where('id', $orderItemId)->firstOrFail();

            if(!$checkItems){
                throw new ModelNotFoundException('Ürün bulunamadı.');

            }

            if($checkItems->status === 'refunded' || $checkItems->status === 'canceled' || $checkItems->status !== 'confirmed'){
                throw new ModelNotFoundException('Bu ürün iade edilemez.');
                
            }

            $items[] = $checkItems;
        }
        
        return $items;
    }
}