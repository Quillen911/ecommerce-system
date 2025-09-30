<?php

namespace Database\Seeders\Main;

use Illuminate\Database\Seeder;
use App\Models\VariantAttribute;

class VariantAttributeSeeder extends Seeder
{
    public function run()
    {
        // 1. Varyant -> Siyah + 3-4 Yaş
        VariantAttribute::create([
            'variant_id' => 1, 
            'attribute_id' => 1, 
            'option_id' => 4
        ]); // Renk: Siyah

        VariantAttribute::create([
            'variant_id' => 1, 
            'attribute_id' => 2, 
            'option_id' => 7
        ]); // Yaş: 8

        VariantAttribute::create([
            'variant_id' => 2, 
            'attribute_id' => 1, 
            'option_id' => 4
        ]); // Renk: Siyah

        VariantAttribute::create([
            'variant_id' => 2, 
            'attribute_id' => 2, 
            'option_id' => 9
        ]); // Yaş: 10

        VariantAttribute::create([
            'variant_id' => 3, 
            'attribute_id' => 1, 
            'option_id' => 4
        ]); // Renk: Siyah

        VariantAttribute::create([
            'variant_id' => 3, 
            'attribute_id' => 2, 
            'option_id' => 11
        ]); // Yaş: 12

        // 2. Varyant ->
        VariantAttribute::create([
            'variant_id' => 4, 
            'attribute_id' => 1, 
            'option_id' => 3
        ]); // Renk: Yeşil

        VariantAttribute::create([
            'variant_id' => 4, 
            'attribute_id' => 2, 
            'option_id' => 9
        ]); // Yaş: 10
        VariantAttribute::create([
            'variant_id' => 5, 
            'attribute_id' => 1, 
            'option_id' => 3
        ]); // Renk: Yeşil

        VariantAttribute::create([
            'variant_id' => 5, 
            'attribute_id' => 2, 
            'option_id' => 11
        ]); // Yaş: 12
 // Yaş: 5-6
    }
}
