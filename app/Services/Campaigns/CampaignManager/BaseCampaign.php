<?php

namespace App\Services\Campaigns\CampaignManager;

use App\Models\Campaign;
use App\Models\CampaignCondition;
use App\Models\CampaignDiscount;
use App\Models\Store;

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
        $value = $condition ? json_decode($condition->condition_value, true) : null;

        if(is_string($value) && (str_starts_with($value, '{') || str_starts_with($value, '['))){
            return json_decode($value, true);
        }
        
        if(is_string($value) && strpos($value, '\\u') !== false){
            $value = json_decode(json_encode($value), true);
        }
        
        return $value;
    }

    protected function isCampaignActive(): bool
    {
        if($this->campaign->starts_at > now() || $this->campaign->ends_at < now()){
            $this->campaign->is_active = 0;
            $this->campaign->save();
            return false;
        }
        $usage_count = $this->campaign->campaign_user_usages()->where('user_id', auth()->user()->id)->count();
        
        if($usage_count && $this->campaign->usage_limit_for_user <= $usage_count){
            return false;
        }
        return true;
    }
    protected function productEligible($products): bool
    {
        $storeIds = collect($products)->pluck('store_id')->toArray();
        //dd($storeIds);
        $storeIds = array_unique($storeIds);
        $storeIds = array_filter($storeIds);
        $storeIds = array_values($storeIds);
        $store = Store::whereIn('id', $storeIds)->first();
        if($store->id != $this->campaign->store_id){
            return false;
        }
        return true;
    }

    protected function getDiscountRule()
    {
        return CampaignDiscount::where('campaign_id', $this->campaign->id)->first();
    }


}