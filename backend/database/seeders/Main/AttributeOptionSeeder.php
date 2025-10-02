<?php

namespace Database\Seeders\Main;

use Illuminate\Database\Seeder;
use App\Models\AttributeOption;

class AttributeOptionSeeder extends Seeder
{
    public function run()
    {
        // Yaş Aralıkları / Bedenler
        $ages = [
            ['6 Yaş', '6-yas'],
            ['7 Yaş', '7-yas'],
            ['8 Yaş', '8-yas'],
            ['9 Yaş', '9-yas'],
            ['10 Yaş', '10-yas'],
            ['11 Yaş', '11-yas'],
            ['12 Yaş', '12-yas'],
            ['13 Yaş', '13-yas'],
            ['14 Yaş', '14-yas'],
            ['15 Yaş', '15-yas'],
            ['16 Yaş', '16-yas'],
        ];

        foreach ($ages as $age) {
            AttributeOption::create([
                'attribute_id' => 1, // Yaş Aralığı
                'value' => $age[0],
                'slug'  => $age[1],
            ]);
        }
    }
}
