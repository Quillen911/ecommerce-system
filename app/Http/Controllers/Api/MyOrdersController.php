<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;

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
}