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
        
        return view('order', array_merge($bag, ['creditCards' => $creditCards]));
    }

    public function done(OrderRequest $request)
    {
        try {
            $user = $this->getUser();
            $bag = Bag::where('bag_user_id', $user->id)->first();

            $selectedCreditCard = $request->input('credit_card_id');
            if(!$selectedCreditCard){
                return redirect('order')->with('error', 'Lütfen bir ödeme yöntemi seçiniz!');
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
            
            $products = $bag->bagItems()->with('product.category')->get();
            if($products->isEmpty()){
                return redirect('main')->with('error', 'Sepetiniz boş!');
            }
            
            $result = $this->orderService->createOrder($user, $products, $this->campaignManager, $selectedCreditCard, $tempCardData, $saveNewCard);
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