<?php

namespace Database\Seeders\Campaigns;

use Illuminate\Database\Seeder;
use App\Models\Campaign;
use App\Models\Product;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CampaignProductSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $campaignIds = Campaign::pluck('id', 'name');
        $productIds  = Product::pluck('id', 'slug');

        $rows = [
            [
                'campaign_id' => $campaignIds['Kışa Özel %20'] ?? 1,
                'product_id'  => $productIds['erkek-cocuk-esofman-takimi'] ?? 1,
            ],
            [
                'campaign_id' => $campaignIds['Kışa Özel %20'] ?? 1,
                'product_id'  => $productIds['kiz-cocuk-jean-pantolon'] ?? 2,
            ],
            [
                'campaign_id' => $campaignIds['3 Al 2 Öde Kids'] ?? 3,
                'product_id'  => $productIds['kaliteli-esofman-4'] ?? 1,
            ],
        ];

        foreach ($rows as $row) {
            DB::table('campaign_products')->updateOrInsert(
                ['campaign_id' => $row['campaign_id'], 'product_id' => $row['product_id']],
                ['created_at' => $now, 'updated_at' => $now]
            );
        }
    }
}
