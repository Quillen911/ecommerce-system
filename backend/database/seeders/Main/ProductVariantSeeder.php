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
            'sku' => 'ESF-SIY-8Y',
            'price' => 200,
            'price_cents' => 20000,
            'stock_quantity' => 13,
            'is_popular' => true,
            'is_active' => true,
        ]);
        ProductVariant::create([
            'product_id' => 1,
            'sku' => 'ESF-SIY-10Y',
            'price' => 200,
            'price_cents' => 20000,
            'stock_quantity' => 7,
            'is_popular' => true,
            'is_active' => true,
        ]);
        ProductVariant::create([
            'product_id' => 1,
            'sku' => 'ESF-SIY-12Y',
            'price' => 200,
            'price_cents' => 20000,
            'stock_quantity' => 12,
            'is_popular' => true,
            'is_active' => true,
        ]);

        ProductVariant::create([
            'product_id' => 1,
            'sku' => 'ESF-YES-10Y',
            'price' => 200,
            'price_cents' => 20000,
            'stock_quantity' => 8,
            'is_popular' => true,
            'is_active' => true,
        ]);
        ProductVariant::create([
            'product_id' => 1,
            'sku' => 'ESF-YES-12Y',
            'price' => 200,
            'price_cents' => 20000,
            'stock_quantity' => 12,
            'is_popular' => true,
            'is_active' => true,
        ]);
    }
}
