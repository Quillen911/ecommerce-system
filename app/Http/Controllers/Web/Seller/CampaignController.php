<?php

namespace App\Http\Controllers\Web\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Seller\CampaignService;
use App\Http\Requests\Seller\Campaign\CampaignStoreRequest;
use App\Http\Requests\Seller\Campaign\CampaignUpdateRequest;
use App\Repositories\Contracts\Store\StoreRepositoryInterface;
use App\Repositories\Contracts\Campaign\CampaignRepositoryInterface;
class CampaignController extends Controller
{
    protected $campaignService;
    protected $storeRepository;
    protected $campaignRepository;

    public function __construct(CampaignService $campaignService, StoreRepositoryInterface $storeRepository, CampaignRepositoryInterface $campaignRepository)
    {
        $this->campaignService = $campaignService;
        $this->storeRepository = $storeRepository;
        $this->campaignRepository = $campaignRepository;
    }

    public function campaign()
    {
        try {
            $seller = auth('seller_web')->user();
            if (!$seller) {
                return redirect()->route('seller.login')->with('error', 'Lütfen giriş yapınız');
            }

            $campaigns = $this->campaignService->getCampaigns($seller->id);
            return view('Seller.Campaign.campaign', compact('campaigns'));

        } catch (\Exception $e) {
            return redirect()->route('seller.campaign')->with('error', $e->getMessage());
        }
    }

    public function storeCampaign()
    {
        return view('Seller.Campaign.storeCampaign');
    }

    public function createCampaign(CampaignStoreRequest $request)
    {
        try {
            $seller = auth('seller_web')->user();
            if (!$seller) {
                return redirect()->route('seller.login')->with('error', 'Lütfen giriş yapınız');
            }
    
            $campaign = $this->campaignService->createCampaign($seller->id, $request->all());
            
            return redirect()->route('seller.campaign')->with('success', 'Kampanya başarıyla oluşturuldu');
    
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
        $seller = auth('seller_web')->user();
        if (!$seller) {
            return redirect()->route('seller.login')->with('error', 'Lütfen giriş yapınız');
        }
        $campaigns = $this->campaignService->showCampaign($seller->id, $id);
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
        $seller = auth('seller_web')->user();
        if (!$seller) {
            return redirect()->route('seller.login')->with('error', 'Lütfen giriş yapınız');
        }
        
        $campaignData = $request->all();
        if (isset($campaignData['data'])) {
            foreach ($campaignData['data'] as $key => $value) {
                $campaignData[$key] = $value;
            }
        }
        
        $campaigns = $this->campaignService->updateCampaign($seller->id, $campaignData, $id);
        
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
        $seller = auth('seller_web')->user();
        if (!$seller) {
            return redirect()->route('seller.login')->with('error', 'Lütfen giriş yapınız');
        }
        $campaigns = $this->campaignService->deleteCampaign($seller->id, $id);
        return redirect()->route('seller.campaign')->with('success', 'Kampanya başarıyla silindi');
    }
    

}
