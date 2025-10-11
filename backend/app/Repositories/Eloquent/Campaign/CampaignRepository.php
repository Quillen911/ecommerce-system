<?php

namespace App\Repositories\Eloquent\Campaign;

use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Contracts\Campaign\CampaignRepositoryInterface;
use App\Models\Campaign;

class CampaignRepository extends BaseRepository implements CampaignRepositoryInterface
{
    public function __construct(Campaign $model)
    {
        $this->model = $model;
    }
    public function getActiveCampaigns()
    {
        return $this->model->where('is_active', 1)->orderBy('id')->get();
    }
    public function getActiveCampaign($campaignId)
    {
        return $this->model->where('is_active', 1)->where('id', $campaignId)->first();
    }
    
    public function getCampaignsByStoreId($storeId)
    {
        return $this->model->with('campaignProducts', 'campaignCategories')->where('store_id', $storeId)->orderBy('id')->get();
    }
    public function getCampaignByStoreId($storeId,$id)
    {
        return $this->model->with('campaignProducts', 'campaignCategories')->where('store_id', $storeId)->where('id', $id)->first();
    }
    public function createCampaign(array $campaignData)
    {
        return $this->model->create($campaignData);
    }
    public function updateCampaign(array $campaignData, $id)
    {
        $campaign = $this->model->find($id);
        if($campaign){
            return $campaign->update($campaignData);
        }
        return false;
    }
    public function deleteCampaign($id)
    {
        return $this->model->find($id)->delete();
    }
}