<?php

namespace App\Services\Campaigns\Admin;

use App\Models\Campaign;
use App\Models\CampaignUserUsage;
use App\Http\Requests\Admin\Campaign\CampaignStoreRequest;
use App\Http\Requests\Admin\Campaign\CampaignUpdateRequest;
class CampaignService
{
    public function indexCampaign()
    {
        $campaigns = Campaign::orderBy('id')->get();
        return $campaigns;
    }
    public function createCampaign(CampaignStoreRequest $request)
    {
        $campaigns = Campaign::create($request->all());
        return $campaigns;
    }
    public function showCampaign($id)
    {
        try {
            $campaign = Campaign::findOrFail($id);
            return $campaign;
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return null;
        }
    }
    public function updateCampaign(CampaignUpdateRequest $request, $id)
    {
        try {
            $campaign = Campaign::findOrFail($id);
            $campaign->update($request->all());
            return $campaign;
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return null;
        }
    }

    public function deleteCampaign($id)
    {
        try {
            $campaign = Campaign::findOrFail($id);
            $campaign->delete();
            return $campaign;
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return null;
        }
    }

}