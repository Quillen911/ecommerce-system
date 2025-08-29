<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Services\Campaigns\CampaignManager;
use App\Services\Bag\Contracts\BagInterface;
use App\Services\Order\Contracts\OrderServiceInterface;
use App\Helpers\ResponseHelper;
use App\Models\Order;
use App\Repositories\Contracts\AuthenticationRepositoryInterface;
use App\Models\Bag;
use App\Models\CreditCard;
class OrderController extends Controller
{
    protected $orderService;
    protected $bagService;
    protected $campaignManager;
    protected $authenticationRepository;
    public function __construct(
        OrderServiceInterface $orderService, 
        BagInterface $bagService, 
        CampaignManager $campaignManager,
        AuthenticationRepositoryInterface $authenticationRepository
    )
    {
        $this->orderService = $orderService;
        $this->bagService = $bagService;
        $this->campaignManager = $campaignManager;
        $this->authenticationRepository = $authenticationRepository;
    }

    public function index()
    {
        $user = $this->authenticationRepository->getUser();
        if(!$user){
            return ResponseHelper::error('Kullanıcı bulunamadı.', 404);
        }
        $orders = Order::where('bag_user_id', $user->id)->get();
        if($orders->isEmpty()){
            return ResponseHelper::notFound('Sipariş bulunamadı.');
        }
        return ResponseHelper::success('Siparişler', $orders);
    }

    public function store(Request $request)
    {
        $user = $this->authenticationRepository->getUser();
        if(!$user){
            return ResponseHelper::error('Kullanıcı bulunamadı.', 404);
        }
        $bag = Bag::where('bag_user_id', $user->id)->first();

        if(!$bag){
            return ResponseHelper::error('Sepetiniz bulunamadı!');
        }
        $selectedCreditCard = $request->input('credit_card_id');
        if(!$selectedCreditCard){
            return ResponseHelper::error('Lütfen bir kredi kartı seçiniz!');
        }

        $products = $bag->bagItems()->with('product.category')->get();

        if($products->isEmpty()){
            return ResponseHelper::notFound('Sepetiniz boş!');
        }
        // İlk ödeme için kart bilgilerini kontrol et
        $tempCardData = null;
        $creditCard = CreditCard::find($selectedCreditCard);
        if ($creditCard && !$creditCard->iyzico_card_token) {
            // İlk ödeme - kart bilgileri gerekli
            $request->validate([
                'card_number' => 'required|string|size:16',
                'cvv' => 'required|string|size:3'
            ]);
            
            $tempCardData = [
                'card_number' => $request->card_number,
                'cvv' => $request->cvv
            ];
        }
        
        $result = $this->orderService->createOrder($user, $products, $this->campaignManager, $selectedCreditCard, $tempCardData);
        if($result instanceof \Exception){
            return ResponseHelper::error($result->getMessage());
        }
        if(is_array($result) && isset($result['success'])){
            if($result['success']){
                $bag->bagItems()->delete();
                Cache::flush();
                return ResponseHelper::success('Sipariş oluşturuldu.', $products);
            }else{
                return ResponseHelper::error($result['error']);
            }
        }
        return ResponseHelper::error('Beklenmeyen bir hata oluştu!' , $result['error']);
    }

    public function show($id)
    {
        $user = $this->authenticationRepository->getUser();
        if(!$user){
            return ResponseHelper::error('Kullanıcı bulunamadı.', 404);
        }
        $order = $this->orderService->showOrder($user->id, $id);
        if(!$order){
            return ResponseHelper::notFound('Sipariş bulunamadı.');
        }
        return ResponseHelper::success('Sipariş', $order);
    }

}