<?php

namespace Database\Seeders\Main;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $product = Product::create([
            'store_id' => 1,
            'title' => 'Eşofman Takımı',
            'category_id' => 5, // Erkek Çocuk > Eşofman Takım
            'description' => 'Rahat ve şık çocuk eşofman takımı.',
            'meta_title' => 'Çocuk Eşofman Takımı',
            'meta_description' => 'Çocuklar için kaliteli eşofman takımı',
            'total_sold_quantity' => 0,
            'is_published' => true,
        ]);
    }
}
