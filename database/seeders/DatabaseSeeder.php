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
            \Database\Seeders\Main\UserSeeder::class,
            \Database\Seeders\Main\CategorySeeder::class,
            \Database\Seeders\Main\ProductSeeder::class,
            \Database\Seeders\Campaigns\CampaignSeeder::class,
            \Database\Seeders\Campaigns\CampaignConditionSeeder::class,
            \Database\Seeders\Campaigns\CampaignDiscountSeeder::class,
        ]);
    }
}
