<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Bag;
use App\Models\BagItem;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use App\Jobs\CreateOrderJob;
use App\Models\OrderItem;

class OrderController extends Controller
{
    public function order()
    {
        $user = auth()->user();
        $bag = $user->bag;
        $products = $bag ? $bag->bagItems()->with('product.category')->get() : collect(); 
        return view('order', compact('products'));
    }

    public function ordergo(Request $request)
    {
        $user = auth()->user();
        $bag = $user->bag;
        if (!$bag) {
            return redirect()->route('bag')->with('error', 'Sepetiniz yok!');
        }
        $products = $bag->bagItems()->with('product.category')->orderBy('id')->get();

        if($products->isEmpty()){
            return redirect()->route('bag')->with('error', 'Sepetiniz boş!');
        }
        $totalPrice = 0;
        foreach($products as $p){
            $totalPrice += $p->quantity * $p->product->list_price;
        }
        $order = Order::create([
            'Bag_User_id' => $user->id,
            'price' => $totalPrice,
            'status' => 'pending',
        ]);
        foreach($products as $item){
            $orderItem =OrderItem::create([
                'order_id' => $order->id, 
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->list_price,
            ]);
            CreateOrderJob::dispatch($orderItem);
        }
        
        
        $bag->bagItems()->delete();
        return redirect()->route('main')->with('success', 'Siparişiniz işleme alındı!');   
    }

    public function CreateOrderJob()
    {
        CreateOrderJob::dispatch();
        return 'Job Kuyruğa eklendi';
    }

}