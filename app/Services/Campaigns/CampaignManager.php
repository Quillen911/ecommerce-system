<?php 

namespace App\Services\Campaigns;

use App\Models\Campaign;
use App\Models\CampaignDiscount;
use App\Services\Campaigns\CampaignRegistry;

class CampaignManager 
{
    private $registry;
    public function __construct(CampaignRegistry $registry)
    {
        $this->registry = $registry;
    }
    public function getBestCampaigns(array $products, $campaigns)
    {  
        $best = ['discount' => 0, 'description' => '', 'campaign_id' => null, 'store_name' => null];
        foreach ($campaigns as $campaign) {
            $service = $this->createServiceByType($campaign);

            if ($service && $service->isApplicable($products)) {
                $result = $service->calculateDiscount($products);
                if ($result['discount'] > $best['discount']) {
                    $best = $result;
                    $best['campaign_id'] = $campaign->id;
                    $best['store_name'] = $campaign->store_name;
                }
            }
        }
        return $best;
    }

    private function createServiceByType(Campaign $campaign)
    {
        if($campaign->is_active == 0 ){
            return null;
        }
        return $this->registry->create($campaign->type, $campaign);
    }

    public function userEligible(Campaign $campaign)
    {
        $usage_count = $campaign->campaign_user_usages()->where('user_id', auth()->user()->id)->first();
        if($usage_count){
            if($campaign->usage_limit_for_user <= $usage_count->usage_count){
                return false;
            }
            $campaign->campaign_user_usages()->create([
                'user_id' => auth()->user()->id,
                'campaign_id' => $campaign->id,
                'campaign_name' => $campaign->name,
                'used_at' => now(),
            ]);
        }else{
            $campaign->campaign_user_usages()->create([
                'user_id' => auth()->user()->id,
                'campaign_id' => $campaign->id,
                'campaign_name' => $campaign->name,
                'used_at' => now(),
            ]);
        }
        
        return true;
    }
    public function decreaseUserUsageCount(Campaign $campaign)
    {
        $campaign->campaign_user_usages()->where('campaign_id', $campaign->id)->where('user_id', auth()->user()->id)->first()->delete();
        $campaign->save();
    }
    public function decreaseUsageLimit(Campaign $campaign)
    {
        $campaign->usage_limit = $campaign->usage_limit - 1;
        if($campaign->usage_limit <= 0){
            $campaign->is_active = 0;
        }
        $campaign->save();
    }
    public function increaseUsageLimit(Campaign $campaign)
    {
        $campaign->usage_limit = $campaign->usage_limit + 1;
        if($campaign->usage_limit > 0 && $campaign->is_active == 0){
            $campaign->is_active = 1;
        }
        $campaign->save();
    }
}