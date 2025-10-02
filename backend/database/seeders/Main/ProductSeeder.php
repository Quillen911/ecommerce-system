<?php

namespace Database\Seeders\Main;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\VariantSize;
use App\Models\Inventory;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // ÜRÜN 1: Erkek Çocuk Eşofman Takımı - 2 Varyant
        $product1 = Product::create([
            'store_id' => 1,
            'title' => 'Erkek Çocuk Eşofman Takımı',
            'slug' => 'erkek-cocuk-esofman-takimi',
            'brand' => 'Nike',
            'category_id' => 8, // erkek-cocuk-esofman-takim
            'gender_id' => 1, // erkek-cocuk
            'description' => 'Kaliteli pamuklu erkek çocuk eşofman takımı',
            'is_published' => true,
            'total_sold_quantity' => 0,
        ]);

        // Varyant 1 - Mavi
        ProductVariant::create([
            'product_id' => $product1->id,
            'sku' => 'ESF-ERK-001-MAV',
            'slug' => 'erkek-esofman-mavi',
            'color_name' => 'Mavi',
            'color_code' => '#0066CC',
            'price_cents' => 34900,
            'is_popular' => true,
            'is_active' => true,
        ]);

        // Varyant 2 - Siyah
        ProductVariant::create([
            'product_id' => $product1->id,
            'sku' => 'ESF-ERK-001-SYH',
            'slug' => 'erkek-esofman-siyah',
            'color_name' => 'Siyah',
            'color_code' => '#000000',
            'price_cents' => 34900,
            'is_active' => true,
        ]);

        // Varyant 1 için bedenler (id=1)
        $this->createVariantSizes(1, 'ESF-ERK-001-MAV', 34900);
        // Varyant 2 için bedenler (id=2)
        $this->createVariantSizes(2, 'ESF-ERK-001-SYH', 34900);

        // ÜRÜN 2: Kız Çocuk Jean Pantolon - 3 Varyant
        $product2 = Product::create([
            'store_id' => 1,
            'title' => 'Kız Çocuk Jean Pantolon',
            'slug' => 'kiz-cocuk-jean-pantolon',
            'brand' => 'H&M',
            'category_id' => 9, // kiz-cocuk-jean
            'gender_id' => 2, // kiz-cocuk
            'description' => 'Şık ve rahat kız jean pantolonu',
            'is_published' => true,
            'total_sold_quantity' => 0,
        ]);

        // Varyant 3 - Açık Mavi
        ProductVariant::create([
            'product_id' => $product2->id,
            'sku' => 'JEAN-KIZ-001-AMAV',
            'slug' => 'kiz-jean-acik-mavi',
            'color_name' => 'Açık Mavi',
            'color_code' => '#87CEEB',
            'price_cents' => 27900,
            'is_active' => true,
        ]);

        // Varyant 4 - Koyu Mavi
        ProductVariant::create([
            'product_id' => $product2->id,
            'sku' => 'JEAN-KIZ-001-KMAV',
            'slug' => 'kiz-jean-koyu-mavi',
            'color_name' => 'Koyu Mavi',
            'color_code' => '#003366',
            'price_cents' => 27900,
            'is_active' => true,
        ]);

        // Varyant 5 - Siyah
        ProductVariant::create([
            'product_id' => $product2->id,
            'sku' => 'JEAN-KIZ-001-SYH',
            'slug' => 'kiz-jean-siyah',
            'color_name' => 'Siyah',
            'color_code' => '#000000',
            'price_cents' => 27900,
            'is_active' => true,
        ]);

        // Varyant 3 için bedenler (id=3)
        $this->createVariantSizes(3, 'JEAN-KIZ-001-AMAV', 27900);
        // Varyant 4 için bedenler (id=4)
        $this->createVariantSizes(4, 'JEAN-KIZ-001-KMAV', 27900);
        // Varyant 5 için bedenler (id=5)
        $this->createVariantSizes(5, 'JEAN-KIZ-001-SYH', 27900);
    }

    private function createVariantSizes($variantId, $skuPrefix, $priceCents)
    {
        // 6-16 yaş için attribute_option_id 1-11
        for ($i = 1; $i <= 11; $i++) {
            $variantSize = VariantSize::create([
                'product_variant_id' => $variantId,
                'size_option_id' => $i,
                'sku' => $skuPrefix . '-' . ($i + 5) . 'YAS',
                'price_cents' => $priceCents,
                'is_active' => true,
            ]);

            $onHand = rand(10, 50);
            $reserved = rand(0, 5);

            Inventory::create([
                'variant_size_id' => $variantSize->id,
                'warehouse_id' => 1,
                'on_hand' => $onHand,
                'reserved' => $reserved,
                'available' => $onHand - $reserved,
                'min_stock_level' => 5,
            ]);
        }
    }
}