<?php

namespace Database\Seeders\Main;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Valorant
            [
                "store_id" => 1,
                "store_name" => "i&d",
                "title" => "Valorant Jett Hesabı",
                "slug" => "valorant-jett-hesabi",
                "category_id" => 1, // Oyunlar
                "description" => "Valorant oyununda Jett karakteri 3D Yazıcı Model.",
                "meta_title" => "Valorant Jett",
                "meta_description" => "Valorant Jett karakter",
                "list_price" => 100.00,
                "list_price_cents" => 10000,
                "stock_quantity" => 5,
                "sold_quantity" => 0,
                "is_published" => true,
                "images" => ["jett.jpg", "jett-2.jpg"],
            ],
            [
                "store_id" => 1,
                "store_name" => "i&d",
                "title" => "Valorant Vandal Skin",
                "slug" => "valorant-vandal-skin",
                "category_id" => 1, // Oyunlar
                "description" => "Valorant için popüler Vandal skin 3D Yazıcı Model.",
                "meta_title" => "Valorant Vandal Skin 3D Yazıcı Model",
                "meta_description" => "Valorant Vandal skin uygun fiyatla stokta.",
                "list_price" => 50.00,
                "list_price_cents" => 5000,
                "stock_quantity" => 10,
                "sold_quantity" => 2,
                "is_published" => true,
                "images" => ["vandal.jpg"],
            ],

            // CS:GO
            [
                "store_id" => 1,
                "store_name" => "i&d",
                "title" => "CS:GO AK-47 Redline",
                "slug" => "csgo-ak47-redline",
                "category_id" => 1, // Oyunlar
                "description" => "CS:GO için en popüler AK-47 Redline skin 3D Yazıcı Model.",
                "meta_title" => "CS:GO AK-47 Redline Skin",
                "meta_description" => "CS:GO AK-47 Redline skin 3D Yazıcı Model",
                "list_price" => 70.00,
                "list_price_cents" => 7000,
                "stock_quantity" => 3,
                "sold_quantity" => 1,
                "is_published" => true,
                "images" => ["ak47-redline.jpg"],
            ],

            // LoL
            [
                "store_id" => 1,
                "store_name" => "i&d",
                "title" => "League of Legends Ahri",
                "slug" => "lol-ahri",
                "category_id" => 1, // Oyunlar
                "description" => "LoL’de popüler şampiyon Ahri 3D Yazıcı Model.",
                "meta_title" => "League of Legends Ahri",
                "meta_description" => "LoL Ahri karakteri 3D Yazıcı Model",
                "list_price" => 120.00,
                "list_price_cents" => 12000,
                "stock_quantity" => 4,
                "sold_quantity" => 0,
                "is_published" => true,
                "images" => ["ahri.jpg"],
            ],

            // Fortnite
            [
                "store_id" => 1,
                "store_name" => "i&d",
                "title" => "Fortnite Skull Trooper Skin",
                "slug" => "fortnite-skull-trooper",
                "category_id" => 1, // Oyunlar
                "description" => "Fortnite Skull Trooper skin 3D Yazıcı Model.",
                "meta_title" => "Fortnite Skull Trooper",
                "meta_description" => "Fortnite Skull Trooper skin 3D Yazıcı Model",
                "list_price" => 90.00,
                "list_price_cents" => 9000,
                "stock_quantity" => 7,
                "sold_quantity" => 1,
                "is_published" => true,
                "images" => ["skull-trooper.jpg"],
            ],
            [
                "store_id" => 1,
                "store_name" => "i&d",
                "title" => "Fortnite Raven Skin",
                "slug" => "fortnite-raven-skin",
                "category_id" => 1, // Oyunlar
                "description" => "Fortnite Raven skin 3D Yazıcı Model.",
                "meta_title" => "Fortnite Raven Skin",
                "meta_description" => "Fortnite Raven skin 3D Yazıcı Model",
                "list_price" => 95.00,
                "list_price_cents" => 9500,
                "stock_quantity" => 6,
                "sold_quantity" => 1,
                "is_published" => true,
                "images" => ["raven.jpg"],
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
