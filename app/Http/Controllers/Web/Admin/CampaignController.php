<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Services\Campaigns\Admin\CampaignService;

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
       // $campaigns = $this->campaignService->createCampaign();
        return view('Admin.Campaign.storeCampaign');
    }

    public function editCampaign($id)
    {
        // $campaigns = $this->campaignService->editCampaign($id);
        return view('Admin.Campaign.editCampaign');
    }

    public function updateCampaign(Request $request, $id)
    {
        // $campaigns = $this->campaignService->updateCampaign($request, $id);
        return view('Admin.Campaign.updateCampaign');
    }

    public function deleteCampaign($id)
    {
        // $campaigns = $this->campaignService->deleteCampaign($id);
        return view('Admin.Campaign.deleteCampaign');
    }
    

}