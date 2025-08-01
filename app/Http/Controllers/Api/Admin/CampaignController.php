<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Helpers\ResponseHelper;
use App\Services\Campaigns\Admin\CampaignService;
use App\Http\Requests\Admin\Campaign\CampaignStoreRequest;
use App\Http\Requests\Admin\Campaign\CampaignUpdateRequest;


class CampaignController extends Controller
{
    protected $campaignService;

    public function __construct(CampaignService $campaignService)
    {
        $this->campaignService = $campaignService;
    }

    public function index()
    {
        $campaigns = $this->campaignService->indexCampaign();
        if($campaigns->isEmpty()){
            return ResponseHelper::notFound('Kampanya bulunamadı');
        }
        return ResponseHelper::success('Kampanyalar listelendi',$campaigns);
    }

    public function store(CampaignStoreRequest $request)
    {
        $campaigns = $this->campaignService->createCampaign($request);
        if(!$campaigns){
            return ResponseHelper::error('Kampanya oluşturulamadı');
        }
        return ResponseHelper::success('Kampanya başarıyla oluşturuldu',$campaigns);
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
        $campaigns = $this->campaignService->updateCampaign($request, $id);
        if(!$campaigns){
            return ResponseHelper::notFound('Kampanya bulunamadı');
        }
        return ResponseHelper::success('Kampanya başarıyla güncellendi',$campaigns);
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