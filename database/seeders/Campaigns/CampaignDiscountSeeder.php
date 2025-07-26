<?php

namespace Database\Seeders\Campaigns;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CampaignDiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CampaignDiscount::create([
            'campaign_id' => 1,
            'discount_type' => 'x_buy_y_pay',
            'discount_value' => 1,
            'applies_to' => 'product',
        ]);
    }
}
