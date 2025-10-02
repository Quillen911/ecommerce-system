<?php

namespace Database\Seeders\Main;

use Illuminate\Database\Seeder;
use App\Models\ProductVariantImage;

class ProductVariantImageSeeder extends Seeder
{
    public function run()
    {
        // Varyant 1 için 2 resim bağlama
        ProductVariantImage::create([
            'product_variant_id' => 1,
            'image' => 'xxllWS3ES9cgshwd6asqwHlqXL1TA9a4FtdFx6rC.png',
            'is_primary' => true,
            'sort_order' => 1,
        ]);

        ProductVariantImage::create([
            'product_variant_id' => 1,
            'image' => 'QAEAY6rHh0RPTh4qo96scasAYhaXELtA3RoBaMeu.png',
            'is_primary' => false,
            'sort_order' => 2,
        ]);

        ProductVariantImage::create([
            'product_variant_id' => 2,
            'image' => 'esofman.png',
            'is_primary' => true,
            'sort_order' => 1,
        ]);

        ProductVariantImage::create([
            'product_variant_id' => 2,
            'image' => 'esofman1.png',
            'is_primary' => false,
            'sort_order' => 2,
        ]);

        ProductVariantImage::create([
            'product_variant_id' => 2,
            'image' => 'esofman2.png',
            'is_primary' => false,
            'sort_order' => 3,
        ]);

    }
}