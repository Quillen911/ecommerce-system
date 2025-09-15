<?php

namespace App\Http\Controllers\Web\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//use App\Services\Payments\IyzicoPaymentService;
use App\Repositories\Contracts\AuthenticationRepositoryInterface;
//use App\Repositories\Contracts\Store\StoreRepositoryInterface;
class SellerSettingsController extends Controller
{
    //protected $iyzicoService;
    protected $authenticationRepository;
    //protected $storeRepository;
    public function __construct(AuthenticationRepositoryInterface $authenticationRepository /*, IyzicoPaymentService $iyzicoService, StoreRepositoryInterface $storeRepository*/)
    {
        $this->authenticationRepository = $authenticationRepository;
      //  $this->iyzicoService = $iyzicoService;
      //  $this->storeRepository = $storeRepository;
    }
    public function index()
    {
        $sellerInfo = $this->authenticationRepository->getSeller();
        return view('Seller.settings', compact('sellerInfo'));
    }

    public function store(Request $request)
    {

        $sellerInfo = auth('seller')->user();
        $sellerInfo->update($request->all());
        $sellerInfo->save();
        return redirect()->route('settings.index');


        /*$sellerInfo = $this->authenticationRepository->getSeller();
        $store = $this->storeRepository->getStoreBySellerId($sellerInfo->id);
        if (!$store) {
            return redirect()->route('settings.index')
            ->with('error', 'Mağaza bulunamadı!');
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'email' => 'nullable|email|max:255',
            'iban' => 'required|string|max:50',           
            'tax_number' => 'required|string|max:20',     
            'tax_office' => 'required|string|max:100',    
            'identity_number' => 'required|string|max:20', 
        ]);
        $store->update($validated);
        if (!$store->sub_merchant_key) {
            $subMerchantKey = $this->iyzicoService->createSubMerchantForStore($sellerInfo, $store);
            dd($subMerchantKey);
            if ($subMerchantKey['success']) {
                $store->sub_merchant_key = $subMerchantKey['sub_merchant_key'];
                $store->save();
            }
        }
        return redirect()->route('settings.index')
        ->with('success', 'Mağaza ayarları güncellendi!');*/
        
    }
}