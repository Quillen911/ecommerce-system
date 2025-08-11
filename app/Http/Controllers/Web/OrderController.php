<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Bag;
use App\Models\BagItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\Campaign;
use Illuminate\Support\Facades\Cache;
use App\Models\OrderItem;
use App\Services\Campaigns\CampaignManager\CampaignManager;
use App\Services\Campaigns\CampaignManager\SabahattinAliCampaign;
use App\Services\Campaigns\CampaignManager\LocalAuthorCampaign;
use App\Http\Requests\OrderRequest;
use App\Traits\UserBagTrait;
use App\Services\OrderService;
use App\Services\BagService;

class OrderController extends Controller
{

    use UserBagTrait;
    protected $orderService;
    protected $bagService;

    public function __construct(OrderService $orderService, BagService $bagService)
    {
        $this->orderService = $orderService;
        $this->bagService = $bagService;
    }

    public function order()
    {
        $bag = $this->bagService->getIndexBag();
        return view('order', $bag);
    }

    public function done(Request $request)
    {
        
        $user = $this->getUser();
        $bag = $this->getUserBag();
        if(!$bag){
            return redirect('main')->with('error', 'Sepetiniz bulunamadı!');
        }
        $selectedCreditCard = $request->input('credit_card_id');
        if(!$selectedCreditCard){
            return redirect('order')->with('error', 'Lütfen bir kredi kartı seçiniz!');
        }
        $products = $bag->bagItems()->with('product.category')->get();
        if($products->isEmpty()){
            return redirect('main')->with('error', 'Sepetiniz boş!');
        }
        $result = $this->orderService->createOrder($user, $products, new CampaignManager(), $selectedCreditCard);
        
        if($result instanceof \Exception){
            return redirect('order')->with('error', $result->getMessage());
        }
        if(is_array($result) && isset($result['success'])){
            if($result['success']){
                $bag->bagItems()->delete();
                Cache::flush();
                return redirect('main')->with('success', 'Siparişiniz başarıyla oluşturuldu!');
            }else{
                return redirect('order')->with('error', $result['error']);
            }
        }else{
            return redirect('order')->with('error', 'Beklenmeyen bir hata oluştu!');
        }
    }
    


}