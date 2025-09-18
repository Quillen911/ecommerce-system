<?php

namespace App\Repositories\Contracts\Campaign;

use App\Repositories\Contracts\BaseRepositoryInterface;

interface CampaignRepositoryInterface extends BaseRepositoryInterface
{
    public function getActiveCampaigns();
    public function getCampaignsByStoreId($storeId);
    public function getCampaignByStoreId($storeId,$id);
    public function createCampaign(array $campaignData);
    public function updateCampaign(array $campaignData, $id);
    public function deleteCampaign($id);
}