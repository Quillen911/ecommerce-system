<?php

namespace App\Services\Campaigns\Admin;

use App\Models\Campaign;
use App\Http\Requests\CampaignStoreRequest;

class CampaignService
{
    public function indexCampaign()
    {
        $campaigns = Campaign::all();
        return $campaigns;
    }
    public function createCampaign(CampaignStoreRequest $request)
    {
        $campaigns = Campaign::create($request->all());
        return $campaigns;
    }
}