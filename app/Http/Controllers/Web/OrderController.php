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
use App\Http\Requests\OrderRequest;
use App\Traits\UserBagTrait;
use App\Services\OrderService;

class OrderController extends Controller
{

    use UserBagTrait;
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

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
        $user = $this->getUser();
        $bag = $this->getUserBag();

        if(!$bag){
            return redirect('main')->with('error', 'Sepetiniz bulunamadı!');
        }

        $products = $bag->bagItems()->with('product.category')->get();

        if($products->isEmpty()){
            return redirect('main')->with('error', 'Sepetiniz boş!');
        }

        $result = $this->orderService->createOrder($user, $products, new CampaignManager());
        
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