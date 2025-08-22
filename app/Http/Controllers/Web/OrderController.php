<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Services\Campaigns\CampaignManager;
use App\Traits\UserBagTrait;
use App\Services\Bag\Contracts\BagInterface;
use App\Services\Order\Contracts\OrderServiceInterface;
use App\Models\CreditCard;
use App\Repositories\Contracts\AuthenticationRepositoryInterface;

class OrderController extends Controller
{

    use UserBagTrait;
    protected $orderService;
    protected $bagService;
    protected $campaignManager;
    protected $authenticationRepository;
    public function __construct(OrderServiceInterface  $orderService, BagInterface $bagService, CampaignManager $campaignManager, AuthenticationRepositoryInterface $authenticationRepository)
    {
        $this->orderService = $orderService;
        $this->bagService = $bagService;
        $this->campaignManager = $campaignManager;
        $this->authenticationRepository = $authenticationRepository;
    }

    public function order()
    {

        $user = $this->authenticationRepository->getUser();
        $bag = $this->bagService->getBag();
        $creditCards = CreditCard::where('user_id', $user->id)->get();
        
        return view('order', array_merge($bag, ['creditCards' => $creditCards]));
    }

    public function done(Request $request)
    {
        try {
            $user = $this->authenticationRepository->getUser();
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
            return redirect('order')->with('error', $e->getMessage());
        }
    }

}