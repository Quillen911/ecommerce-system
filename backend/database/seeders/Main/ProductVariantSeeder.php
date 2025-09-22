<?php

namespace Database\Seeders\Main;

use Illuminate\Database\Seeder;
use App\Models\ProductVariant;

class ProductVariantSeeder extends Seeder
{
    public function run()
    {
        ProductVariant::create([
            'product_id' => 1,
            'sku' => 'ESF-RED-3Y',
            'price' => 200,
            'price_cents' => 20000,
            'stock_quantity' => 10,
            'images' => ['esofman-kirmizi.jpg']
        ]);

        ProductVariant::create([
            'product_id' => 1,
            'sku' => 'ESF-BLUE-5Y',
            'price' => 200,
            'price_cents' => 20000,
            'stock_quantity' => 8,
            'images' => ['esofman-mavi.jpg']
        ]);
    }
}
