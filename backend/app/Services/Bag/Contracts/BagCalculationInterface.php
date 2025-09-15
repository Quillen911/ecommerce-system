<?php

namespace App\Services\Bag\Contracts;

interface BagCalculationInterface
{
    public function getBestCampaign($bagItems);
    public function calculateTotal($bagItems);
    public function calculateCargoPrice($total);
    public function calculateDiscount($bestCampaign);
}