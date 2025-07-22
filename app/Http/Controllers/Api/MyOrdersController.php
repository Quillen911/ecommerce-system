<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Cache;
use App\Traits\UserBagTrait;
use App\Helpers\ResponseHelper;

class MyOrdersController extends Controller
{
    use UserBagTrait;
    public function index(Request $request)
    {
        $user = $this->getUser();
        $orders = Order::with(['orderItems.product.category'])
            ->where('Bag_User_id', $user->id)
            ->orderByDesc('id')
            ->get();
        if($orders->isEmpty()){
            return ResponseHelper::notFound('Sipariş bulunamadı.');
        }

        return ResponseHelper::success('Siparişler', $orders);
    }
    
    public function show(Request $request, $id)
    {
        $user = $this->getUser();
        $order = Order::with(['orderItems.product.category'])
            ->where('Bag_User_id', $user->id)
            ->where('id', $id)
            ->first();
        if(!$order){
            return ResponseHelper::notFound('Sipariş bulunamadı.');
        }
        return ResponseHelper::success('Sipariş', $order);
    }

    public function destroy(Request $request, $id)
    {
        $user = $this->getUser();
        $order = Order::where('Bag_User_id', $user->id)
                    ->where('id', $id)
                    ->first();
        if(!$order){
            return ResponseHelper::notFound('Sipariş bulunamadı.');
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
        return ResponseHelper::success('Sipariş silindi ve ürün stokları güncellendi.', $order);
    }
}