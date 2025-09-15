<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Services\Campaigns\CampaignManager;
use App\Services\Bag\Contracts\BagInterface;
use App\Services\Order\Contracts\OrderServiceInterface;
use App\Models\CreditCard;
use App\Repositories\Contracts\AuthenticationRepositoryInterface;
use App\Models\Bag;
use App\Traits\GetUser;
use App\Http\Requests\OrderRequest;
use App\Models\UserAddress;

class OrderController extends Controller
{
    use GetUser;
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
        $bag = $this->bagService->getBag();
        $user = $this->getUser();
        $creditCards = CreditCard::where('user_id', $user->id)->get();
        $addresses = UserAddress::where('user_id', $user->id)->get();
        return view('order', array_merge($bag, ['creditCards' => $creditCards, 'addresses' => $addresses]));
    }

    public function done(OrderRequest $request)
    {
        try {
            $user = $this->getUser();
            $bag = Bag::where('bag_user_id', $user->id)->first();

            $selectedCreditCard = $request->input('credit_card_id');
            $selectedShippingAddress = $request->input('shipping_address_id');
            $selectedBillingAddress = $request->input('billing_address_id');
            
            if(!$selectedCreditCard){
                return redirect('order')->with('error', 'Lütfen bir ödeme yöntemi seçiniz!');
            }
            if(!$selectedShippingAddress){
                return redirect('order')->with('error', 'Lütfen bir teslimat adresi seçiniz!');
            }
            if(!$selectedBillingAddress){
                return redirect('order')->with('error', 'Lütfen bir fatura adresi seçiniz!');
            }
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
                    return redirect('order')->with('error', 'Seçilen kart bulunamadı!');
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
            
            $products = $bag->bagItems()->with('product.category')->get();
            if($products->isEmpty()){
                return redirect('main')->with('error', 'Sepetiniz boş!');
            }
            
            $result = $this->orderService->createOrder($user, $products, $this->campaignManager, $selectedCreditCard, $tempCardData, $saveNewCard, $selectedShippingAddress, $selectedBillingAddress);
            if($result['success']){
                $bag->bagItems()->delete();
                Cache::tags(['bag', 'products'])->flush();
                return redirect('main')->with('success', 'Siparişiniz başarıyla oluşturuldu!');
            } else {
                return redirect('order')->with('error', $result['error']);
            }
            
        } catch (\Exception $e) {
            return redirect('order')->with('error', $e->getMessage());
        }
    }

}