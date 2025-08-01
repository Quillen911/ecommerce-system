<?php

namespace App\Services\Campaigns\CampaignManager;

interface CampaignInterface
{
    public function isApplicable(array $products): bool;
    public function calculateDiscount(array $products): array;
}