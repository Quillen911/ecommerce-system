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
        $campaign = Campaign::where('is_active', 1)->where('description', $order->campaign_info)->first();
        if($campaign){
            $campaign->usage_limit = $campaign->usage_limit + 1;
            $userUsage = $campaign->user_usage ?? [];
            $userUsage[$userId] = ($userUsage[$userId] ?? 0) - 1;
            $campaign->user_usage = $userUsage;
            $campaign->save();
            if($userUsage <= $campaign->usage_limit_for_user){
                $campaign->user_activity = 1;
                $campaign->save();
            }
        }

        $order->delete();
        Cache::flush();

        return $order;
    }
}