<?php

namespace App\Http\Controllers\Web\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;

class SellerOrderController extends Controller
{
    public function sellerOrders()
    {
        $orders = Order::all();
        return view('Seller.sellerorders', compact('orders'));
    }
}
