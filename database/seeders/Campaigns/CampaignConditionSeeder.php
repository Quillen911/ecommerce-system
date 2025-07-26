<?php

namespace Database\Seeders\Campaigns;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CampaignCondition;

class CampaignConditionSeeder extends Seeder
{
    public function run(): void
    {
        CampaignCondition::create([
            'campaign_id' => 1,
            'condition_type' => 'author',
            'condition_value' => json_encode('Sabahattin Ali'),
            'operator' => '=',
        ]);

        CampaignCondition::create([
            'campaign_id' => 1,
            'condition_type' => 'category',
            'condition_value' => json_encode('Roman'),
            'operator' => '=',
        ]);
    }
}
