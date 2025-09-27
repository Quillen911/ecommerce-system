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
            \Database\Seeders\SellerSeeder::class,
            \Database\Seeders\StoreSeeder::class,
            \Database\Seeders\Main\UserSeeder::class,
            \Database\Seeders\Main\CategorySeeder::class,
            \Database\Seeders\Main\AttributeSeeder::class,
            \Database\Seeders\Main\AttributeOptionSeeder::class,
            \Database\Seeders\Main\ProductSeeder::class,
            \Database\Seeders\Main\ProductVariantSeeder::class,
            \Database\Seeders\Main\VariantAttributeSeeder::class,
            \Database\Seeders\Main\ProductVariantImageSeeder::class,
            \Database\Seeders\Campaigns\CampaignSeeder::class,
            \Database\Seeders\Campaigns\CampaignConditionSeeder::class,
            \Database\Seeders\Campaigns\CampaignDiscountSeeder::class,
        ]);
    }
}
