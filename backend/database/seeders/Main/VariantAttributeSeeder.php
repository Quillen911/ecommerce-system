<?php

namespace Database\Seeders\Main;

use Illuminate\Database\Seeder;
use App\Models\VariantAttribute;

class VariantAttributeSeeder extends Seeder
{
    public function run(): void
    {
        $common = [
            'attribute_id'      => 1,          // Yaş Aralığı
        ];

        $pairs = [
            ['variant_id' => 1, 'option_id' => 1],
            ['variant_id' => 1, 'option_id' => 2],
            ['variant_id' => 1, 'option_id' => 3],
            ['variant_id' => 1, 'option_id' => 4],
            ['variant_id' => 1, 'option_id' => 5],
            ['variant_id' => 1, 'option_id' => 6],
            ['variant_id' => 1, 'option_id' => 7],
            ['variant_id' => 1, 'option_id' => 8],
            ['variant_id' => 1, 'option_id' => 9],
            ['variant_id' => 1, 'option_id' => 10],
            ['variant_id' => 1, 'option_id' => 11],

            ['variant_id' => 2, 'option_id' => 1],
            ['variant_id' => 2, 'option_id' => 2],
            ['variant_id' => 2, 'option_id' => 3],
            ['variant_id' => 2, 'option_id' => 4],
            ['variant_id' => 2, 'option_id' => 5],
            ['variant_id' => 2, 'option_id' => 6],
            ['variant_id' => 2, 'option_id' => 7],
            ['variant_id' => 2, 'option_id' => 8],
            ['variant_id' => 2, 'option_id' => 9],
            ['variant_id' => 2, 'option_id' => 10],
            ['variant_id' => 2, 'option_id' => 11],
        ];

        foreach ($pairs as $pair) {
            VariantAttribute::create(array_merge($common, $pair));
        }
    }

}