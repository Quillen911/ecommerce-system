<?php

namespace App\Services\Campaigns;

use App\Models\Campaign;

interface CampaignInterface
{
    public function isApplicable(array $products): bool;
    public function calculateDiscount(array $products): array;
    public function setCampaign(Campaign $campaign): void;
}