<?php 

namespace App\Services\Campaigns;

class CampaignManager 
{
    protected $campaigns;

    public function __construct()
    {
        $this->campaigns =[
            new SabahattinAliCampaign(),
            new LocalAuthorCampaign(),
            new TwoHundreadsCampaign(),
        ];
    }

    public function getBestCampaigns(array $products)
    {
        $best = ['discount' =>0, 'description' =>''];
        foreach($this->campaigns as $campaign){
            if($campaign->isApplicable($products)){
                $result = $campaign->calculateDiscount($products);
                if($result['discount'] > $best['discount']){
                    $best = $result;
                }
            }
        }
        return $best;
    }

}