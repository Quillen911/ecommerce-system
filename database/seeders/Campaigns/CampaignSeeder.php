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
            'per_user_limit' => 1,
            'starts_at' => now(),
            'ends_at' => now()->addMonth(),
        ]);
    }
}
