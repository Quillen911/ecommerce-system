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
            'store_name' => 'i&d',
            'title' => 'Çocuk Eşofman Takımı',
            'slug' => 'cocuk-esofman-takimi',
            'category_id' => 2, // örn: Erkek Çocuk > Eşofman Takım
            'description' => 'Rahat ve şık çocuk eşofman takımı.',
            'meta_title' => 'Çocuk Eşofman Takımı',
            'meta_description' => 'Çocuklar için kaliteli eşofman takımı',
            'list_price' => 200,
            'list_price_cents' => 20000,
            'stock_quantity' => 0, // varyantlar stok tutacak
            'sold_quantity' => 0,
            'is_published' => true,
            'images' => ['esofman.jpg']
        ]);
    }
}
