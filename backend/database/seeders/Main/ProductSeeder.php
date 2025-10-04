<?php

namespace Database\Seeders\Main;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantImage;
use App\Models\VariantSize;
use App\Models\Inventory;
use App\Models\ProductCategory;
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
            'category_id' => 6, // erkek-cocuk-esofman-takim (ID 6)
            'gender_id' => null, // category.gender_id'den alınacak
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
        
        // Varyant 1 ve 2 için resimler
        $this->createVariantImages(1, [
            ['image' => 'xxllWS3ES9cgshwd6asqwHlqXL1TA9a4FtdFx6rC.png', 'is_primary' => true, 'sort_order' => 1],
            ['image' => 'QAEAY6rHh0RPTh4qo96scasAYhaXELtA3RoBaMeu.png', 'is_primary' => false, 'sort_order' => 2],
        ]);
        
        $this->createVariantImages(2, [
            ['image' => 'esofman.png', 'is_primary' => true, 'sort_order' => 1],
            ['image' => 'esofman1.png', 'is_primary' => false, 'sort_order' => 2],
            ['image' => 'esofman2.png', 'is_primary' => false, 'sort_order' => 3],
        ]);

        // ÜRÜN 2: Kız Çocuk Jean Pantolon - 3 Varyant
        $product2 = Product::create([
            'store_id' => 1,
            'title' => 'Kız Çocuk Jean Pantolon',
            'slug' => 'kiz-cocuk-jean-pantolon',
            'brand' => 'H&M',
            'category_id' => 7, // kiz-cocuk-jean (ID 7)
            'gender_id' => null, // category.gender_id'den alınacak
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

        // Varyant 3 için bedenler (id=3) - Açık Mavi Jean
        $this->createVariantSizes(3, 'JEAN-KIZ-001-AMAV', 27900);
        // Varyant 4 için bedenler (id=4) - Koyu Mavi Jean
        $this->createVariantSizes(4, 'JEAN-KIZ-001-KMAV', 27900);
        // Varyant 5 için bedenler (id=5) - Siyah Jean
        $this->createVariantSizes(5, 'JEAN-KIZ-001-SYH', 27900);
        
        // Varyant 3, 4, 5 için resimler (placeholder)
        $this->createVariantImages(3, [
            ['image' => 'jean-acik-mavi.png', 'is_primary' => true, 'sort_order' => 1],
        ]);
        
        $this->createVariantImages(4, [
            ['image' => 'jean-koyu-mavi.png', 'is_primary' => true, 'sort_order' => 1],
        ]);
        
        $this->createVariantImages(5, [
            ['image' => 'jean-siyah.png', 'is_primary' => true, 'sort_order' => 1],
        ]);

        // Product 1 için çoklu kategori ilişkileri
        ProductCategory::create([
            'product_id' => $product1->id,
            'category_id' => 6, // erkek-cocuk-esofman-takim (spesifik)
            'is_primary' => true,
        ]);
        
        ProductCategory::create([
            'product_id' => $product1->id,
            'category_id' => 3, // esofman-takim (genel/parent)
            'is_primary' => false,
        ]);
        
        // Product 2 için çoklu kategori ilişkileri
        ProductCategory::create([
            'product_id' => $product2->id,
            'category_id' => 7, // kiz-cocuk-jean (spesifik)
            'is_primary' => true,
        ]);
        
        ProductCategory::create([
            'product_id' => $product2->id,
            'category_id' => 1, // jean (genel/parent)
            'is_primary' => false,
        ]);
    }

    private function createVariantSizes($variantId, $skuPrefix, $priceCents)
    {
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

    private function createVariantImages($variantId, $images)
    {
        foreach ($images as $imageData) {
            ProductVariantImage::create([
                'product_variant_id' => $variantId,
                'image' => $imageData['image'],
                'is_primary' => $imageData['is_primary'],
                'sort_order' => $imageData['sort_order'],
            ]);
        }
    }
}