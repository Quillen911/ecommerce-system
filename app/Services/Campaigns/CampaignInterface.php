<?php

namespace App\Services\Campaigns;

interface CampaignInterface
{
    public function isApplicable(array $products): bool;
    public function calculateDiscount(array $products): array;
}