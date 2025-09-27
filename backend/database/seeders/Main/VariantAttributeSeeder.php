<?php

namespace Database\Seeders\Main;

use Illuminate\Database\Seeder;
use App\Models\VariantAttribute;

class VariantAttributeSeeder extends Seeder
{
    public function run()
    {
        // 1. Varyant -> Kırmızı + 3-4 Yaş
        VariantAttribute::create([
            'variant_id' => 1, 
            'attribute_id' => 1, 
            'option_id' => 4
        ]); // Renk: Kırmızı

        VariantAttribute::create([
            'variant_id' => 1, 
            'attribute_id' => 2, 
            'option_id' => 7
        ]); // Yaş: 3-4

        // 2. Varyant -> Mavi + 5-6 Yaş
        VariantAttribute::create([
            'variant_id' => 2, 
            'attribute_id' => 1, 
            'option_id' => 3
        ]); // Renk: Mavi

        VariantAttribute::create([
            'variant_id' => 2, 
            'attribute_id' => 2, 
            'option_id' => 10
        ]); // Yaş: 5-6
    }
}
