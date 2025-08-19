<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Services\Campaigns\CampaignManager\CampaignManager;
use App\Traits\UserBagTrait;
use App\Services\Bag\Contracts\BagInterface;
use App\Services\Order\Contracts\OrderServiceInterface;
use App\Models\CreditCard;

class OrderController extends Controller
{

    use UserBagTrait;
    protected $orderService;
    protected $bagService;
    protected $campaignManager;
    public function __construct(OrderServiceInterface  $orderService, BagInterface $bagService, CampaignManager $campaignManager)
    {
        $this->orderService = $orderService;
        $this->bagService = $bagService;
        $this->campaignManager = $campaignManager;
    }

    public function order()
    {
        $user = $this->getUser();
        $bag = $this->bagService->getBag();
        $creditCards = CreditCard::where('user_id', $user->id)->get();
        
        return view('order', array_merge($bag, ['creditCards' => $creditCards]));
    }

    public function done(Request $request)
    {
        try {
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
            
            $result = $this->orderService->createOrder($user, $products, $this->campaignManager, $selectedCreditCard);
            
            if($result['success']){
                $bag->bagItems()->delete();
                Cache::flush();
                return redirect('main')->with('success', 'Siparişiniz başarıyla oluşturuldu!');
            } else {
                return redirect('order')->with('error', $result['error']);
            }
            
        } catch (\Exception $e) {
            \Log::error('beklenmeyen hata oluştu', [
                'error' => $e->getMessage(),
                'user' => $user->id
            ]);
            
            return redirect('order')->with('error', 'Beklenmeyen bir hata oluştu!');
        }
    }

}