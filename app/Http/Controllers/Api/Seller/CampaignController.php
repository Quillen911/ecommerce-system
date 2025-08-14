<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Helpers\ResponseHelper;
use App\Services\Campaigns\Seller\CampaignService;
use App\Http\Requests\Seller\Campaign\CampaignStoreRequest;
use App\Http\Requests\Seller\Campaign\CampaignUpdateRequest;
use App\Models\Store;

class CampaignController extends Controller
{
    protected $campaignService;

    public function __construct(CampaignService $campaignService)
    {
        $this->campaignService = $campaignService;
    }

    public function index()
    {
        $seller = auth('seller')->user();
        $store = Store::where('seller_id', $seller->id)->first();
        $storeId = $store->id;
        if(!$store){
            return ResponseHelper::error('Lütfen giriş yapınız');
        }

        $campaigns = $this->campaignService->indexCampaign($storeId);
        if($campaigns->isEmpty()){
            return ResponseHelper::notFound('Kampanya bulunamadı');
        }
        return ResponseHelper::success('Kampanyalar listelendi',$campaigns);
    }

    public function store(CampaignStoreRequest $request)
    {
        try{
        $campaigns = $this->campaignService->createCampaign($request);
        if(!$campaigns){
            return ResponseHelper::error('Kampanya oluşturulamadı');
        }
        return ResponseHelper::success('Kampanya başarıyla oluşturuldu',$campaigns);
        }
        catch(\Exception $e){
            return ResponseHelper::error('Kampanya oluşturulamadı');
        }
    }

    public function show($id)
    {
        $campaigns = $this->campaignService->showCampaign($id);
        if(!$campaigns){
            return ResponseHelper::notFound('Kampanya bulunamadı');
        }
        return ResponseHelper::success('Kampanya detayı',$campaigns);
    }

    public function update(CampaignUpdateRequest $request, $id)
    {
        try{
        $campaigns = $this->campaignService->updateCampaign($request, $id);
        if(!$campaigns){
            return ResponseHelper::notFound('Kampanya bulunamadı');
        }
        return ResponseHelper::success('Kampanya başarıyla güncellendi',$campaigns);
        }
        catch(\Exception $e){
            return ResponseHelper::error('Kampanya güncellenemedi');
        }
    }
    
    public function destroy($id)
    {
        $campaigns = $this->campaignService->deleteCampaign($id);
        if(!$campaigns){
            return ResponseHelper::notFound('Kampanya bulunamadı');
        }
        return ResponseHelper::success('Kampanya başarıyla silindi',$campaigns);
    }
    
    

    
}
