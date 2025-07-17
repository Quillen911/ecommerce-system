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
        $products = $bag->bagItems()->with('product.category')->orderBy('id')->get();

        if($products->isEmpty()){
            return redirect()->route('bag')->with('error', 'Sepetiniz boş!');
        }
        $totalPrice = 0;
        foreach($products as $p){
            $totalPrice += $p->quantity * $p->product->list_price;
        }
        
            $orderData = Order::create([
                'Bag_User_id' => $user->id,
                'product_id' => $products->product_id,
                'quantity' => $products->quantity,
                'price' => $totalPrice,
                'status' => 'pending',
            ]);
            CreateOrderJob::dispatch($orderData);
                
        
        $bag->bagItems()->delete();
        return redirect()->route('main')->with('success', 'Siparişiniz işleme alındı!');   
    }

    public function myorders()
    {
        $user = auth()->user();
        $orders = Order::with(['product.category'])
            ->where('Bag_User_id', $user->id)
            ->orderByDesc('id')
            ->get();
        return view('myorders', compact('orders'));
    }
    public function CreateOrderJob()
    {
        CreateOrderJob::dispatch();
        return 'Job Kuyruğa eklendi';
    }

    public function listmyorders()
    {   
        $user = auth()->user();
        $orders = Order::where('Bag_User_id', $user->id)->get();
        return view('listmyorders',compact('orders'));
    }

}