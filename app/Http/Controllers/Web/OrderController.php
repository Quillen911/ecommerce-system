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
use App\Services\Campaigns\CampaignManager;
use App\Services\Campaigns\SabahattinAliCampaign;
use App\Services\Campaigns\LocalAuthorCampaign;

class OrderController extends Controller
{
    public function order()
    {
        //sepet ve ürünleri getirir.
        $user = auth()->user();
        $bag = Bag::where('Bag_User_id', $user->id)->first();
        $products = $bag ? $bag->bagItems()->with('product.category')->orderBy('id')->get() : collect();
        
        $campaignManager = new CampaignManager();

        $bestCampaign = $campaignManager->getBestCampaigns($products->all());
        
        $total = $products->sum(function($item){
            return $item->quantity * $item->product->list_price;
        });

        $cargoPrice = $total >= 50 ? 0 : 10;
        
        $discount = $bestCampaign['discount'] ?? 0;

        $Totally = $total +$cargoPrice -$discount;

        return view('order', compact('products', 'bestCampaign', 'total', 'cargoPrice', 'discount', 'Totally'));
    }

    public function done(Request $request)
    {
        $user = auth()->user();
        $bag = Bag::where('Bag_User_id', $user->id)->first();
        $products = $bag->bagItems()->with('product.category')->get();
        if(!$bag){
            return redirect('bag')->with('error', 'Sepetiniz boş!');
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
                return redirect('order')->with('error', 'Ürün stokta yok!');
            }
        }

        $bag->bagItems()->delete();
        Cache::flush();
        return redirect('main')->with('success', 'Siparişiniz işleme alındı!');
    }

    public function CreateOrderJob()
    {
        CreateOrderJob::dispatch();
        return 'Job Kuyruğa eklendi';
    }

}