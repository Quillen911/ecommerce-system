<?php

namespace Database\Seeders\Main;

use Illuminate\Database\Seeder;
use App\Models\AttributeOption;

class AttributeOptionSeeder extends Seeder
{
    public function run()
    {
        // Renkler
        AttributeOption::create([
            'attribute_id' => 1, 
            'value' => 'Kırmızı', 
            'slug' => 'kirmizi'
        ]);
        AttributeOption::create([
            'attribute_id' => 1, 
            'value' => 'Mavi', 
            'slug' => 'mavi'
        ]);
        AttributeOption::create([
            'attribute_id' => 1, 
            'value' => 'Yeşil', 
            'slug' => 'yesil'
        ]);

        // Yaş Aralığı
        AttributeOption::create([
            'attribute_id' => 2, 
            'value' => '3-4 Yaş', 
            'slug' => '3-4-yas'
        ]);
        AttributeOption::create([
            'attribute_id' => 2, 
            'value' => '5-6 Yaş', 
            'slug' => '5-6-yas'
        ]);
        AttributeOption::create([
            'attribute_id' => 2, 
            'value' => '7-8 Yaş', 
            'slug' => '7-8-yas'
        ]);
    }
}
