<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Helpers\ResponseHelper;
use App\Services\Campaigns\Admin\CampaignService;
use App\Http\Requests\CampaignStoreRequest;

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
        return ResponseHelper::success('Kampanyalar listelendi',$campaigns);
    }

    public function store(CampaignStoreRequest $request)
    {
        $campaigns = $this->campaignService->createCampaign($request);
        return ResponseHelper::success('Kampanya başarıyla oluşturuldu',$campaigns);
    }

    public function show($id)
    {
        //$campaigns = $this->campaignService->showCampaign($id);
        return ResponseHelper::success();
    }

    public function update(Request $request, $id)
    {
        //$campaigns = $this->campaignService->updateCampaign($request, $id);
        return ResponseHelper::success();
    }
    
    public function destroy($id)
    {
        //$campaigns = $this->campaignService->deleteCampaign($id);
        return ResponseHelper::success();
    }
    
    

    
}