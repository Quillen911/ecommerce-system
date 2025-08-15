<?php

namespace Database\Seeders\Campaigns;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CampaignCondition;

class CampaignConditionSeeder extends Seeder
{
    public function run(): void
    {
        //Sabahattin Ali Kampanyası
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


        //200 TL ve üzeri alışverişlerde sipariş toplamına %5 indirim
        CampaignCondition::create([
            'campaign_id' => 2,
            'condition_type' => 'min_bag',
            'condition_value' => json_encode(200.00),
            'operator' => '>=',
        ]);


        //Yerli Yazarlarda %5 indirim
        CampaignCondition::create([
            'campaign_id' => 3,
            'condition_type' => 'author',
            'condition_value' => json_encode(['Yaşar Kemal', 'Oğuz Atay', 'Sabahattin Ali', 'Hakan Mengüç', 'Uğur Koşar', 'Mert Arık', 'Peyami Safa'], JSON_UNESCAPED_UNICODE),
            'operator' => 'in',
        ]);
        CampaignCondition::create([
            'campaign_id' => 4,
            'condition_type' => 'min_bag',
            'condition_value' => json_encode(200.00),
            'operator' => '>=',
        ]);
        CampaignCondition::create([
            'campaign_id' => 5,
            'condition_type' => 'author',
            'condition_value' => json_encode('Dostoyevski'),
            'operator' => '=',
        ]);
    }
}
