<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Bag;
use App\Models\BagItem;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use App\Jobs\CreateOrderJob;
use App\Models\OrderItem;
use App\Services\Campaigns\CampaignManager;
use App\Services\Campaigns\SabahattinAliCampaign;
use App\Services\Campaigns\LocalAuthorCampaign;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $orders = Order::where('Bag_User_id', $user->id)->get();
        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $bag = Bag::where('Bag_User_id', $user->id)->first();
        $products = $bag->bagItems()->with('product.category')->get();
        if(!$bag){
            return response()->json(['message' => 'Sepetiniz boş!'], 400);
        }
        
        $campaignManager = new CampaignManager();
        $bestCampaign = $campaignManager->getBestCampaigns($products->all());
        $total = $products->sum(function($items) {
            return $items->quantity * $items->product->list_price; 
        });

        $cargoPrice = $total >= 50 ? 0 : 10;

        $discount = $bestCampaign['discount'] ?? 0;

        $Totally = $total + $cargoPrice - $discount;
        $campaignInfo = $bestCampaign['description'] ?? '';    
        
        $order = Order::create([
            'Bag_User_id' => $user->id,
            'price' => $total + $cargoPrice,
            'cargo_price' => $cargoPrice,
            'campaign_info' => $campaignInfo,
            'campaing_price' => $Totally,
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

        foreach($products as $p){
            $productTable = Product::find($p->product_id);
            if($productTable && $productTable->stock_quantity >= $p->quantity){
                $productTable->stock_quantity -= $p->quantity;
                $productTable->save();
            }
            else{
                return response()->json(['message' => 'Ürün stokta yok!'], 400);
            }
        }

        $bag->bagItems()->delete();
        Cache::flush();
        return response()->json(['message' => 'Siparişiniz işleme alındı!']);
    }

    public function show(Request $request, $id)
    {
        $user = auth()->user();
        $order = Order::where('Bag_User_id', $user->id)
                    ->where('id',$id)
                    ->first();
        if(!$order){
            return response()->json(['message' => 'Sipariş bulunamadı.'], 404);
        }
        return response()->json($order);
    }

    public function destroy(Request $request,$id)
    {
        $user = auth()->user();
        $order = Order::where('Bag_User_id', $user->id)
                    ->where('id', $id)
                    ->first();
        if(!$order){
            return response()->json(['message' => 'Sipariş bulunamadı.'], 404);
        }
        $bag = Bag::where('Bag_User_id', $user->id)->first();
        $products = $bag->bagItems()->with('product.category')->get();
        foreach($products as $p){
            $productTable = Product::find($p->product_id);
            if($productTable){
                $productTable->stock_quantity += 1;
                $productTable->save();
            }
        }
        $bag->bagItems()->delete();
        $order->delete();
        return response()->json(['message' => 'Sipariş silindi.']);
    }
}