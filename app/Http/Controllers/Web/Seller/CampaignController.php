<?php

namespace App\Http\Controllers\Web\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Seller\CampaignService;
use App\Http\Requests\Seller\Campaign\CampaignStoreRequest;
use App\Http\Requests\Seller\Campaign\CampaignUpdateRequest;
use App\Repositories\Contracts\Store\StoreRepositoryInterface;
class CampaignController extends Controller
{
    protected $campaignService;
    protected $storeRepository;

    public function __construct(CampaignService $campaignService, StoreRepositoryInterface $storeRepository)
    {
        $this->campaignService = $campaignService;
        $this->storeRepository = $storeRepository;
    }

    public function campaign()
    {
        $seller = auth('seller_web')->user();
        if(!$seller){
            return redirect()->route('seller.login')->with('error', 'Lütfen giriş yapınız');
        }
        $store = $this->storeRepository->getStoreBySellerId($seller->id);
        if(!$store){
            return redirect()->route('seller.campaign')->with('error', 'Lütfen giriş yapınız');
        }
        $campaigns = $this->campaignService->indexCampaign($store->id);
        return view('Seller.Campaign.campaign' ,compact('campaigns'));
    }

    public function storeCampaign()
    {
        return view('Seller.Campaign.storeCampaign');
    }

    public function createCampaign(CampaignStoreRequest $request)
    {
        try {
            $campaigns = $this->campaignService->createCampaign($request);

            if (request()->expectsJson() || request()->header('Accept') === 'application/json') {
                return response()->json([
                    'success' => true,
                    'message' => 'Kampanya başarıyla eklendi',
                    'data' => $campaigns
                ]);
            }
            return redirect()->route('seller.campaign')->with('success', 'Kampanya başarıyla eklendi');
        } catch (\Exception $e) {
            \Log::error('CampaignController - Error creating campaign:', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);
            return redirect()->route('seller.campaign')->with('error', 'Kampanya oluşturulurken bir hata oluştu: ' . $e->getMessage());
        }
    }

    public function editCampaign($id)
    {
        $campaigns = $this->campaignService->showCampaign($id);
        if(!$campaigns){
            return redirect()->route('seller.campaign')->with('error', 'Kampanya bulunamadı');
        }
        
        if (request()->expectsJson() || request()->header('Accept') === 'application/json') {
            return response()->json([
                'success' => true,
                'message' => 'Kampanya bulundu',
                'data' => $campaigns
            ]);
        }
        
        return view('Seller.Campaign.editCampaign' ,compact('campaigns'));
    }

    public function updateCampaign(CampaignUpdateRequest $request, $id)
    {
        $campaigns = $this->campaignService->updateCampaign($request, $id);
        if (request()->expectsJson() || request()->header('Accept') === 'application/json') {
            return response()->json([
                'success' => true,
                'message' => 'Kampanya başarıyla güncellendi',
                'data' => $campaigns
            ]);
        }

        return redirect()->route('seller.campaign')->with('success', 'Kampanya başarıyla güncellendi');
    }

    public function deleteCampaign($id)
    {
        $campaigns = $this->campaignService->deleteCampaign($id);
        return redirect()->route('seller.campaign')->with('success', 'Kampanya başarıyla silindi');
    }
    

}
