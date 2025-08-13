<?php

namespace App\Services\Campaigns\Seller;

use App\Models\OrderItem;

class SellerOrderService
{
    public function getSellerOrders($store)
    {
        $orderItems = OrderItem::with('product')->where('store_id', $store->id)->get();
        return $orderItems;
    }
    public function getSellerOneOrder($store, $id)
    {
        $orderItem = OrderItem::with('product')->where('store_id', $store->id)->where('order_id', $id)->first();
        return $orderItem;
    }
    public function confirmItem($store, $id)
    {
        $orderItem = OrderItem::where('store_id', $store->id)->where('order_id', $id)->first();
        $orderItem->status = 'completed';
        $orderItem->save();
        return $orderItem;
    }
    public function cancelSelectedItems($store, $id)
    {
        $order = Order::where('id', $id)->first();
        return $orderItem;
    }
}