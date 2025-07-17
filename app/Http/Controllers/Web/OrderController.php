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
        $bag = Bag::where('Bag_User_id', $user->id)->first();
        $products = $bag ? $bag->bagItems()->with('product.category')->orderBy('id')->get() : collect(); 
        return view('order', compact('products'));
    }

    public function ordergo()
    {
        $user = auth()->user();
        $bag = Bag::where('Bag_User_id', $user->id)->first();
        $products = $bag->bagItems()->with('product.category')->get();
        if(!$bag){
            return redirect('bag')->with('error', 'Sepetiniz boş!');
        }
        $totalPrice = 0;
        foreach($products as $p){
            $totalPrice += $p->quantity * $p->product->list_price ;
        }
        $cargoPrice = 10;
        $campaingPrice =0;
        if($totalPrice >= 50){   
            $cargoPrice = 0;
        }
        if($totalPrice >= 200 ){
            $campaingPrice = $totalPrice * 95 /100;
        }

           
        $order = Order::create([
            'Bag_User_id' => $user->id,
            'price' => $totalPrice + $cargoPrice ,
            'cargo_price' => $cargoPrice == 0 ? 0 : 10,
            'campaing_price' => $campaingPrice,
            'status' => 'bekliyor',
        ]);

        foreach($products as $p){
            $orderItem = OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $p->product->id,
                'quantity' => $p->quantity,
                'price' => $p->product->list_price,
            ]);
            CreateOrderJob::dispatch($orderItem);
        }
        $bag->bagItems()->delete();
        return redirect('main')->with('success', 'Siparişiniz işleme alındı!');
    }

    public function CreateOrderJob()
    {
        CreateOrderJob::dispatch();
        return 'Job Kuyruğa eklendi';
    }

}