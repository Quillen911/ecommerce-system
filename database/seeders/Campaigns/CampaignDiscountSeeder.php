<?php

namespace Database\Seeders\Campaigns;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CampaignDiscount;

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
            'discount_value' => ['x' => 2, 'y' => 1],
        ]);

        CampaignDiscount::create([
            'campaign_id' => 2,
            'discount_type' => 'percentage',
            'discount_value' => ['percentage' => 5],
        ]);

        CampaignDiscount::create([
            'campaign_id' => 3,
            'discount_type' => 'percentage',
            'discount_value' => ['percentage' => 5],
        ]);
        CampaignDiscount::create([
            'campaign_id' => 4,
            'discount_type' => 'percentage',
            'discount_value' => ['percentage' => 10],
        ]);
        CampaignDiscount::create([
            'campaign_id' => 5,
            'discount_type' => 'x_buy_y_pay',
            'discount_value' => ['x' => 2, 'y' => 1],
        ]);
    }
}
