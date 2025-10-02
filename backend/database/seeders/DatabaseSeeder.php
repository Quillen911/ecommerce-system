<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        
        $this->call([
            \Database\Seeders\Main\GenderSeeder::class,
            \Database\Seeders\Main\CategorySeeder::class,
            \Database\Seeders\Main\AttributeSeeder::class,
            \Database\Seeders\Main\WarehouseSeeder::class,
            \Database\Seeders\SellerSeeder::class,
            \Database\Seeders\StoreSeeder::class,
            \Database\Seeders\Main\ProductSeeder::class,
            \Database\Seeders\Main\UserSeeder::class,
        ]);
    }
}
