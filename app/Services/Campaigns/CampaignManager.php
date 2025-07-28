<?php 

namespace App\Services\Campaigns;

use App\Models\Campaign;

class CampaignManager 
{

    public function getBestCampaigns(array $products, Campaign $campaign)
    {

        $campaignServices = [
            new SabahattinAliCampaign($campaign),
            new TwoHundreadsCampaign($campaign),
        ];

        $best = ['discount' =>0, 'description' =>''];
        foreach($campaignServices as $c){
            if($c->isApplicable($products)){
                $result = $c->calculateDiscount($products);
                if($result['discount'] > $best['discount']){
                    $best = $result;
                }
            }
        }
        return $best;
    }

}