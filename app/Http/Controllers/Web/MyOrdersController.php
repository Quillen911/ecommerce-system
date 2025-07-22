<?php

namespace App\Http\Controllers\Web;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Cache;

class MyOrdersController extends Controller{
    public function myorders()
    {
        $user = auth()->user();
        $orders = Order::with(['orderItems.product.category'])
            ->where('Bag_User_id', $user->id)
            ->orderByDesc('id')
            ->get();
        return view('myorders', compact('orders'));
    }

    public function delete($id)
    {
        $user = auth()->user();
        $order = Order::where('Bag_User_id', $user->id)
                    ->where('id', $id)
                    ->first();
        if(!$order){
            return redirect()->back()->with('error', 'Sipariş bulunamadı.');
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
        return redirect()->back()->with('success', 'Sipariş başarıyla iptal edildi.');
    }
}
