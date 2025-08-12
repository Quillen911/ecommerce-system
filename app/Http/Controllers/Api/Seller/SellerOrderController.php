<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;

class SellerOrderController extends Controller
{
    public function index()
    {
        $orders = Order::all();
        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }
}
