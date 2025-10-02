<?php

namespace Database\Seeders\Main;

use Illuminate\Database\Seeder;
use App\Models\Warehouse;

class WarehouseSeeder extends Seeder
{
    public function run()
    {
        Warehouse::create([
            'name' => 'Merkez Depo',
            'code' => 'MAIN',
            'is_default' => true,
            'is_active' => true,
        ]);
    }
}