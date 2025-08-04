<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Cache;
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
        $campaign = Campaign::where('description', $order->campaign_info)->first();
        if($campaign){
            $campaign->usage_limit = $campaign->usage_limit + 1;
            $campaign->save();
            if($campaign->usage_limit <= 0){
                $campaign->is_active = 0;
            }
        }

        $order->delete();
        Cache::flush();

        return $order;
    }
}