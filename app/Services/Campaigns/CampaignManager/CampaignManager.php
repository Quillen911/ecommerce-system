<?php 

namespace App\Services\Campaigns\CampaignManager;

use App\Models\Campaign;
use App\Models\CampaignDiscount;

class CampaignManager 
{
    public function getBestCampaigns(array $products, $campaigns)
    {  
        $best = ['discount' => 0, 'description' => ''];
        
        foreach ($campaigns as $campaign) {
            $service = $this->createServiceByType($campaign);
            
            if ($service && $service->isApplicable($products)) {
                $result = $service->calculateDiscount($products);
                if ($result['discount'] > $best['discount']) {
                    $best = $result;
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
        switch ($campaign->type) {
            case 'percentage':
                return new PercentageCampaign($campaign);
            case 'fixed':
                return new FixedCampaign($campaign);
            case 'x_buy_y_pay':
                return new XBuyYPayCampaign($campaign);
            default:
                return null;
        }
    }
    
}