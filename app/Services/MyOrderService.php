<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Cache;

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
    
    public function cancelOrder($userId, $orderId)
    {
        $order = Order::where('Bag_User_id', $userId)
                        ->where('id', $orderId)
                        ->first();

        if(!$order){
            return null;
        }

        foreach($order->orderItems as $item){
            $product = $item->product;
            if($product){
                $product->stock_quantity += $item->quantity;
                $product->save();
            }
        }

        $order->delete();
        Cache::flush();

        return $order;
    }
}