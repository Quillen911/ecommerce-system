<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Models\CampaignCondition;
use App\Models\CampaignDiscount;
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

    public function campaign()
    {
        $campaigns = $this->campaignService->indexCampaign();
        return view('Admin.Campaign.campaign' ,compact('campaigns'));
    }

    public function storeCampaign()
    {
        return view('Admin.Campaign.storeCampaign');
    }

    public function createCampaign(CampaignStoreRequest $request)
    {
        try {
            $campaigns = $this->campaignService->createCampaign($request);
            return redirect()->route('admin.campaign')->with('success', 'Kampanya başarıyla eklendi');
        } catch (\Exception $e) {
            \Log::error('CampaignController - Error creating campaign:', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);
            return redirect()->route('admin.campaign')->with('error', 'Kampanya oluşturulurken bir hata oluştu: ' . $e->getMessage());
        }
    }

    public function editCampaign($id)
    {
        $campaigns = $this->campaignService->showCampaign($id);
        if(!$campaigns){
            return redirect()->route('admin.campaign')->with('error', 'Kampanya bulunamadı');
        }
        return view('Admin.Campaign.editCampaign' ,compact('campaigns'));
    }

    public function updateCampaign(CampaignUpdateRequest $request, $id)
    {
        $campaigns = $this->campaignService->updateCampaign($request, $id);
        \Log::info('updateCampaign called', ['id' => $id, 'data' => $request->all()]);

        return redirect()->route('admin.campaign')->with('success', 'Kampanya başarıyla güncellendi');
    }

    public function deleteCampaign($id)
    {
        $campaigns = $this->campaignService->deleteCampaign($id);
        return redirect()->route('admin.campaign')->with('success', 'Kampanya başarıyla silindi');
    }
    

}