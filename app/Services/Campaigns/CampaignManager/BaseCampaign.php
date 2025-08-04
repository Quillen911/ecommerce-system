<?php

namespace App\Services\Campaigns\CampaignManager;

use App\Models\Campaign;
use App\Models\CampaignCondition;
use App\Models\CampaignDiscount;

abstract class BaseCampaign implements CampaignInterface
{
    protected $campaign;
    protected $conditions = [];

    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
        $this->loadConditions();
    }
    
    protected function loadConditions()
    {
        $this->conditions = $this->campaign->conditions->keyBy('condition_type');
    }

    protected function getConditionValue($condition_type)
    {
        $condition = $this->conditions->get($condition_type);
        return $condition ? json_decode($condition->condition_value, true) : null;
    }

    protected function isCampaignActive(): bool
    {
        if($this->campaign->starts_at > now() || $this->campaign->ends_at < now()){
            $this->campaign->is_active = 0;
            $this->campaign->save();
            return false;
        }
        return true;
    }

    protected function getDiscountRule()
    {
        return CampaignDiscount::where('campaign_id', $this->campaign->id)->first();
    }


}