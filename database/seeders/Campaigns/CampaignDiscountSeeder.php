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
            'discount_value' => json_encode(['x' => 2, 'y' => 1]),
            'applies_to' => 'product',
        ]);

        CampaignDiscount::create([
            'campaign_id' => 2,
            'discount_type' => 'percentage',
            'discount_value' => json_encode(['discount' => 0.05]),
            'applies_to' => 'bag_total',
        ]);

        CampaignDiscount::create([
            'campaign_id' => 3,
            'discount_type' => 'percentage',
            'discount_value' => json_encode(['discount' => 0.05]),
            'applies_to' => 'product_author',
        ]);
    }
}
