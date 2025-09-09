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
use App\Http\Requests\OrderRequest;
use App\Models\UserAddress;

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

    public function store(OrderRequest $request)
    {
        \Log::info('[API] order payload', [
            'auth_guard' => auth()->getDefaultDriver(),
            'user_id'    => optional($request->user())->id,
            'has_products' => !empty($this->bagService->getBag($request->user())),
            'shipping_address_id' => $request->input('shipping_address_id'),
            'billing_address_id'  => $request->input('billing_address_id'),
            'credit_card_id'      => $request->input('credit_card_id'),
          ]);
        $user = $this->authenticationRepository->getUser();
        if(!$user){
            return ResponseHelper::error('Kullanıcı bulunamadı.', 404);
        }
        $bag = Bag::where('bag_user_id', $user->id)->first();

        if(!$bag){
            return ResponseHelper::error('Sepetiniz bulunamadı!');
        }
        $selectedCreditCard = $request->input('credit_card_id');
        $selectedShippingAddress = $request->input('shipping_address_id');
        $selectedBillingAddress = $request->input('billing_address_id');
        if(!$selectedCreditCard){
            return ResponseHelper::error('Lütfen bir kredi kartı seçiniz!');
        }
        if(!$selectedShippingAddress){
            return ResponseHelper::error('Lütfen bir teslimat adresi seçiniz!');
        }
        if(!$selectedBillingAddress){
            return ResponseHelper::error('Lütfen bir fatura adresi seçiniz!');
        }

        $products = $bag->bagItems()->with('product.category')->get();

        if($products->isEmpty()){
            return ResponseHelper::notFound('Sepetiniz boş!');
        }
        // İlk ödeme için kart bilgilerini kontrol et
        $tempCardData = null;
        $saveNewCard = false;
        
        if ($selectedCreditCard === 'new_card') {
            $tempCardData = [
                'card_holder_name' => $request->new_card_holder_name,
                'card_name' => $request->new_card_name,
                'card_number' => $request->new_card_number,
                'expire_month' => $request->new_expire_month,
                'expire_year' => $request->new_expire_year,
                'cvv' => $request->new_cvv
            ];
            
            $saveNewCard = $request->boolean('save_new_card');
            
        } else {
            $creditCard = CreditCard::find($selectedCreditCard);
            if (!$creditCard) {
                return ResponseHelper::error('Seçilen kart bulunamadı!');
            }
            
            if (!$creditCard->iyzico_card_token) {
                $tempCardData = [
                    'card_number' => null,
                    'cvv' => $request->existing_cvv
                ];
            }
        }
        if($selectedBillingAddress === 'new_billing_address'){
            $newBillingAddress = UserAddress::create([
                'user_id' => $user->id,
                'title' => $request->new_billing_address_title,
                'first_name' => $request->new_billing_address_first_name,
                'last_name' => $request->new_billing_address_last_name,
                'phone' => $request->new_billing_address_phone,
                'address_line_1' => $request->new_billing_address_address,
                'address_line_2' => $request->new_billing_address_address_2,
                'district' => $request->new_billing_address_district,
                'city' => $request->new_billing_address_city,
                'postal_code' => $request->new_billing_address_postal_code,
                'country' => $request->new_billing_address_country,
                'notes' => $request->new_billing_address_notes,
                'is_default' => false,
                'is_active' => true,
            ]);
            $selectedBillingAddress = $newBillingAddress->id;
            
        }
       // dd($selectedShippingAddress, $selectedBillingAddress);
        $result = $this->orderService->createOrder($user, $products, $this->campaignManager, $selectedCreditCard, $tempCardData, $saveNewCard, $selectedShippingAddress, $selectedBillingAddress);
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