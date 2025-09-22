<?php

namespace Database\Seeders\Main;

use Illuminate\Database\Seeder;
use App\Models\Attribute;

class AttributeSeeder extends Seeder
{
    public function run()
    {
        $color = Attribute::create([
            'name' => 'Renk',
            'code' => 'color',
            'input_type' => 'select',
            'is_filterable' => true,
            'is_required' => true,
            'sort_order' => 1,
        ]);

        $size = Attribute::create([
            'name' => 'Yaş Aralığı',
            'code' => 'age',
            'input_type' => 'select',
            'is_filterable' => true,
            'is_required' => true,
            'sort_order' => 2,
        ]);
    }
}


