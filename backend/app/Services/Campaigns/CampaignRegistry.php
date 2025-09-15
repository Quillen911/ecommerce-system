<?php

namespace App\Services\Campaigns;

use App\Models\Campaign;
use App\Services\Campaigns\CampaignInterface;
use App\Services\Campaigns\Handlers\PercentageCampaign;
use App\Services\Campaigns\Handlers\FixedCampaign;
use App\Services\Campaigns\Handlers\XBuyYPayCampaign;
use Illuminate\Support\Facades\Log;

class CampaignRegistry
{
    private $handlers = [];

    public function __construct()
    {
        $this->registerDefaultHandlers();
    }

    public function create(String $type, Campaign $campaign): CampaignInterface
    {
        if(!isset($this->handlers[$type])){
            Log::warning("Campaign handler not found for type: {$type}");
            return null;
        }
        $handler = clone $this->handlers[$type];
        $handler->setCampaign($campaign);
        return $handler;
    }
    public function getAvailableTypes(): array
    {
        return array_keys($this->handlers);
    }

    private function registerDefaultHandlers(): void
    {
        $this->handlers['percentage'] = new PercentageCampaign(new Campaign());
        $this->handlers['fixed'] = new FixedCampaign(new Campaign());
        $this->handlers['x_buy_y_pay'] = new XBuyYPayCampaign(new Campaign());
    }
}