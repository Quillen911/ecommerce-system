<?php 

namespace App\Services\Campaigns;

use App\Models\Campaign;

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
            return response()->json(['error' => 'Kampanya aktif değil'], 400);
        }
        switch ($campaign->id) {
            case 1:
                return new SabahattinAliCampaign($campaign);
            case 2:
                return new TwoHundreadsCampaign($campaign);
            case 3:
                return new LocalAuthorCampaign($campaign);
            default:
                return null;
        }
    }
}