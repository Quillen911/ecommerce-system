<?php

namespace App\Http\Controllers\Web;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;

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

}
