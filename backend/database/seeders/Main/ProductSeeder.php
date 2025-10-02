<?php

namespace Database\Seeders\Main;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\VariantSize;
use App\Models\Inventory;
use App\Models\ProductCategory;
use App\Models\ProductVariantImage;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $store = \App\Models\Store::first();
        $warehouse = \App\Models\Warehouse::first();

        $products = [
            // 👖 JEAN PANTOLONLAR (GENEL + Cinsiyet)
            [
                'title' => 'Slim Fit Jean Pantolon',
                'slug' => 'slim-fit-jean-pantolon',
                'brand' => 'KidsDenim',
                'gender_id' => 1, // Erkek
                'categories' => [3, 6], // 👉 GENEL Jean(3) + Erkek Jean(6)
                'description' => 'Rahat ve şık slim fit çocuk jean pantolon.',
                'price_cents' => 12990,
                'colors' => ['Mavi', 'Siyah', 'Gri'],
                'sizes' => ['98-104', '110-116', '122-128']
            ],
            [
                'title' => 'Skinny Jean Pantolon', 
                'slug' => 'skinny-jean-pantolon',
                'brand' => 'GirlsDenim',
                'gender_id' => 2, // Kız
                'categories' => [3, 9], // 👉 GENEL Jean(3) + Kız Jean(9)
                'description' => 'Modern ve rahat çocuk skinny jean.',
                'price_cents' => 11990,
                'colors' => ['Mavi', 'Siyah', 'Kırmızı'],
                'sizes' => ['98-104', '110-116', '122-128']
            ],

            // 🪡 KETEN PANTOLONLAR (GENEL + Cinsiyet)  
            [
                'title' => 'Keten Pantolon',
                'slug' => 'keten-pantolon',
                'brand' => 'SummerKids',
                'gender_id' => 1, // Erkek
                'categories' => [4, 7], // 👉 GENEL Keten(4) + Erkek Keten(7)
                'description' => 'Yaz için ideal nefes alabilen keten pantolon.',
                'price_cents' => 14990,
                'colors' => ['Bej', 'Beyaz', 'Yeşil'],
                'sizes' => ['98-104', '110-116', '122-128', '134-140']
            ],
            [
                'title' => 'Kız Keten Pantolon',
                'slug' => 'kiz-keten-pantolon', 
                'brand' => 'SummerGirls',
                'gender_id' => 2, // Kız
                'categories' => [4, 10], // 👉 GENEL Keten(4) + Kız Keten(10)
                'description' => 'Kız çocuklar için şık keten pantolon.',
                'price_cents' => 15990,
                'colors' => ['Pembe', 'Beyaz', 'Mor'],
                'sizes' => ['98-104', '110-116', '122-128']
            ],

            // 🏃 EŞOFMAN TAKIMLAR (GENEL + Cinsiyet)
            [
                'title' => 'Eşofman Takımı',
                'slug' => 'esofman-takimi',
                'brand' => 'ComfyKids', 
                'gender_id' => 2, // Unisex
                'categories' => [5, 8, 11], // 👉 GENEL Eşofman(5) + Erkek(8) + Kız(11)
                'description' => 'Rahat ve yumuşak çocuk eşofman takımı.',
                'price_cents' => 8990,
                'colors' => ['Gri', 'Mavi', 'Siyah'],
                'sizes' => ['98-104', '110-116', '122-128', '134-140']
            ]
        ];

        foreach ($products as $productData) {
            $product = Product::create([
                'store_id' => $store->id,
                'title' => $productData['title'],
                'slug' => $productData['slug'],
                'brand' => $productData['brand'],
                'category_id' => $productData['categories'][0], // Primary category
                'gender_id' => $productData['gender_id'],
                'description' => $productData['description'],
                'is_published' => true,
                'total_sold_quantity' => rand(0, 50)
            ]);

            // ✅ ÇOKLU KATEGORİ ATAMASI
            foreach ($productData['categories'] as $index => $categoryId) {
                ProductCategory::create([
                    'product_id' => $product->id,
                    'category_id' => $categoryId,
                    'is_primary' => $index === 0 // İlk kategori primary
                ]);
            }

            // ✅ DÜZELTİLDİ: foreach parametreleri
            foreach ($productData['colors'] as $colorIndex => $color) {
                $variant = ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => strtoupper(substr($productData['brand'], 0, 3)) . '-' . 
                            $this->getColorCodeForSku($color) . '-' . uniqid(), // ✅ TÜRKÇE KARAKTERSİZ
                    'slug' => $productData['slug'] . '-' . $this->slugify($color), // ✅ SLUGIFY EKLE
                    'color_name' => $color,
                    'color_code' => $this->getColorCode($color),
                    'price_cents' => $productData['price_cents'],
                    'is_active' => true
                ]);

                // ✅ DÜZELTİLDİ: parametre sayısı
                $this->createVariantImages($variant, $color, $productData['title']);

                foreach ($productData['sizes'] as $size) {
                    $sizeOption = \App\Models\AttributeOption::where('value', $size)->first();
                    
                    // ✅ SIZE OPTION KONTROLÜ EKLENDİ
                    if (!$sizeOption) {
                        \Log::warning("Size option bulunamadı: {$size}");
                        continue;
                    }
                    
                    $variantSize = VariantSize::create([
                        'product_variant_id' => $variant->id,
                        'size_option_id' => $sizeOption->id,
                        'sku' => $variant->sku . '-' . $size,
                        'price_cents' => $productData['price_cents'],
                        'is_active' => true
                    ]);

                    Inventory::create([
                        'variant_size_id' => $variantSize->id,
                        'warehouse_id' => $warehouse->id,
                        'on_hand' => rand(10, 50),
                        'reserved' => 0,
                        'min_stock_level' => 5
                    ]);
                }
            }
        }
    }

    // ✅ DÜZELTİLDİ: parametre sayısı
    private function createVariantImages($variant, $color, $productTitle)
    {
        $imageCount = rand(1, 3);
        
        for ($i = 1; $i <= $imageCount; $i++) {
            $imageName = "{$variant->sku}-{$i}.jpg";
            
            ProductVariantImage::create([
                'product_variant_id' => $variant->id,
                'image' => $imageName, 
                'is_primary' => $i === 1,
                'sort_order' => $i
            ]);
        }
    }

    private function getColorCode($color)
    {
        $colorMap = [
            'Siyah' => '#000000', 'Beyaz' => '#FFFFFF', 'Mavi' => '#0000FF',
            'Kırmızı' => '#FF0000', 'Yeşil' => '#008000', 'Gri' => '#808080',
            'Bej' => '#F5F5DC', 'Pembe' => '#FFC0CB', 'Mor' => '#800080'
        ];
        
        return $colorMap[$color] ?? '#CCCCCC';
    }

    private function getColorCodeForSku($color)
    {
        $colorMap = [
            'Siyah' => 'SIY', 'Beyaz' => 'BEY', 'Mavi' => 'MAV',
            'Kırmızı' => 'KIR', 'Yeşil' => 'YES', 'Gri' => 'GRI',
            'Bej' => 'BEJ', 'Pembe' => 'PEM', 'Mor' => 'MOR'
        ];
        
        return $colorMap[$color] ?? 'RENK';
    }

    private function slugify($text)
    {
        // Türkçe karakterleri İngilizce karşılıklarına çevir
        $tr = ['ç', 'ğ', 'ı', 'i', 'ö', 'ş', 'ü', 'Ç', 'Ğ', 'İ', 'I', 'Ö', 'Ş', 'Ü'];
        $en = ['c', 'g', 'i', 'i', 'o', 's', 'u', 'c', 'g', 'i', 'i', 'o', 's', 'u'];
        
        $text = str_replace($tr, $en, $text);
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower($text);
        
        return $text ?: 'n-a';
    }
}