<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Cache;

class MyOrdersController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $orders = Order::with(['orderItems.product.category'])
            ->where('Bag_User_id', $user->id)
            ->orderByDesc('id')
            ->get();
        if(!$orders){
            return response()->json(['message' => 'Sipariş bulunamadı.'], 404);
        }

        return response()->json($orders);
    }
    public function show(Request $request, $id)
    {
        $user = auth()->user();
        $order = Order::with(['orderItems.product.category'])
            ->where('Bag_User_id', $user->id)
            ->where('id', $id)
            ->first();
        if(!$order){
            return response()->json(['message' => 'Sipariş bulunamadı.'], 404);
        }
        return response()->json($order);
    }

    public function destroy(Request $request, $id)
    {
        $user = auth()->user();
        $order = Order::where('Bag_User_id', $user->id)
                    ->where('id', $id)
                    ->first();
        if(!$order){
            return response()->json(['message' => 'Sipariş bulunamadı.'], 404);
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
        return response()->json(['message' => 'Sipariş silindi ve ürün stokları güncellendi.']);
    }
}