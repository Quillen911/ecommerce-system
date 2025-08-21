<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
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

    public function index()
    {
        try {
            $seller = auth('seller')->user();
            $campaigns = $this->campaignService->getCampaigns($seller->id);

            return ResponseHelper::success('Kampanyalar listelendi', $campaigns);

        } catch (\Exception $e) {
            return ResponseHelper::error('Kampanya listeleme hatası: ' . $e->getMessage());
        }
    }

    public function store(CampaignStoreRequest $request)
    {
        try {
            $seller = auth('seller')->user();
            $campaign = $this->campaignService->createCampaign($seller->id, $request->all());
            
            return ResponseHelper::success('Kampanya başarıyla oluşturuldu', $campaign);
    
        } catch (\Exception $e) {
            \Log::error('CampaignController - Error creating campaign:', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);
            
            return ResponseHelper::error('Kampanya oluşturulamadı: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $seller = auth('seller')->user();
            $campaign = $this->campaignService->showCampaign($seller->id, $id);   

            return ResponseHelper::success('Kampanya detayı', $campaign);

        } catch (\Exception $e) {
            return ResponseHelper::error('Kampanya detayı alınamadı: ' . $e->getMessage());
        }
    }

    public function update(CampaignUpdateRequest $request, $id)
    {
        try {

            
            $seller = auth('seller')->user();
            
            // Veriyi düzelt - data içindeki tüm alanları çıkar
            $campaignData = $request->all();
            if (isset($campaignData['data'])) {
                foreach ($campaignData['data'] as $key => $value) {
                    $campaignData[$key] = $value;
                }
            }
            
            $campaign = $this->campaignService->updateCampaign($seller->id, $campaignData, $id);

            return ResponseHelper::success('Kampanya başarıyla güncellendi', $campaign);

        } catch (\Exception $e) {
            return ResponseHelper::error('Kampanya güncellenemedi: ' . $e->getMessage());
        }
    }
    
    public function destroy($id)
    {
        try {
            $seller = auth('seller')->user();
            $campaign = $this->campaignService->deleteCampaign($seller->id, $id);

            return ResponseHelper::success('Kampanya başarıyla silindi', $campaign);

        } catch (\Exception $e) {
            return ResponseHelper::error('Kampanya silinemedi: ' . $e->getMessage());
        }
    }
}
