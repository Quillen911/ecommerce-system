<?php

namespace App\Services\Campaigns;

use App\Models\Campaign;
use App\Models\CampaignCondition;
use App\Models\CampaignDiscount;
use App\Models\Store;
use App\Services\Campaigns\CampaignInterface;

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
        if (!$condition) {
            return null;
        }
        
        $value = $condition->condition_value;
        
        // Eğer value zaten array ise direkt döndür
        if (is_array($value)) {
            return $value;
        }
        
        // Eğer value string ise ve JSON formatında ise decode et
        if (is_string($value)) {
            // JSON formatında olup olmadığını kontrol et
            if (str_starts_with(trim($value), '{') || str_starts_with(trim($value), '[')) {
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    return $decoded;
                }
            }
            
            // Virgülle ayrılmış değerler varsa array'e çevir
            if (strpos($value, ',') !== false) {
                return array_map('trim', explode(',', $value));
            }
            
            // Basit string değer ise direkt döndür
            return $value;
        }
        
        return $value;
    }

    public function setCampaign(Campaign $campaign): void
    {
        $this->campaign = $campaign;
        $this->loadConditions();
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
    protected function productEligible($products): array
    {
        $storeIds = collect($products)->pluck('store_id')->toArray();
    
        $eligibleProducts = [];
        foreach($products as $product){
            if($product->store_id == $this->campaign->store_id){
                $eligibleProducts[] = $product;
            }
        }
        return $eligibleProducts;
    }

    protected function getDiscountRule()
    {
        return CampaignDiscount::where('campaign_id', $this->campaign->id)->first();
    }


}