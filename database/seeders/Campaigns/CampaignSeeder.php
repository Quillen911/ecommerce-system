<?php

namespace Database\Seeders\Campaigns;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Campaign;

class CampaignSeeder extends Seeder
{
    public function run(): void
    {
        Campaign::create([
            'name' => 'Sabahattin Ali Romanlarında 2 Al 1 Öde',
            'type' => 'x_buy_y_pay',
            'description' => 'Sabahattin Ali Romanlarında 2 Al 1 Öde',
            'is_active' => true,
            'usage_limit_for_user' => 3,
            'usage_limit' => 10,
            'starts_at' => now(),
            'ends_at' => now()->addHours(24),
        ]);

        Campaign::create([
            'name' => '200 TL ve üzeri alışverişlerde sipariş toplamına %5 indirim',
            'type' => 'percentage',
            'description' => '200 TL ve üzeri alışverişlerde sipariş toplamına %5 indirim',
            'is_active' => true,
            'usage_limit_for_user' => 1,
            'usage_limit' => 50,
            'starts_at' => now(),
            'ends_at' => now()->addHours(24),
        ]);
        Campaign::create([
            'name' => 'Yerli Yazarlarda %5 indirim',
            'type' => 'percentage',
            'description' => 'Yerli Yazarlarda %5 indirim',
            'is_active' => true,
            'usage_limit_for_user' => 5,
            'usage_limit' => 100,
            'starts_at' => now(),
            'ends_at' => now()->addHours(24),
        ]);
    }
}
