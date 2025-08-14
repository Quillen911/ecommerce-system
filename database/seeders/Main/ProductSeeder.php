<?php

namespace Database\Seeders\Main;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;


class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                "title" => "İnce Memed",
                "category_id" => 1,
                "author" => "Yaşar Kemal",
                "list_price" => 48.75,
                "stock_quantity" => 10, 
                "store_id" => 1,
                "store_name" => "i&d",
                "sold_quantity" => 0,
                "images" => null    
            ],
            [
                "title" => "Tutunamayanlar",
                "category_id" => 1,
                "author" => "Oğuz Atay",
                "list_price" => 90.3,
                "stock_quantity" => 20,
                "store_id" => 1,
                "store_name" => "i&d",
                "sold_quantity" => 0,
                "images" => null
            ],
            [
                "title" => "Kürk Mantolu Madonna",
                "category_id" => 1,
                "author" => "Sabahattin Ali",
                "list_price" => 9.1,
                "stock_quantity" => 4,
                "store_id" => 1,
                "store_name" => "i&d",
                "sold_quantity" => 0,
                "images" => null
            ],
            [
                "title" => "Fareler ve İnsanlar",
                "category_id" => 1,
                "author" => "John Steinback",
                "list_price" => 35.75,
                "stock_quantity" => 8,
                "store_id" => 1,
                "store_name" => "i&d",
                "sold_quantity" => 0,
                "images" => null
            ],
            [
                "title" => "Şeker Portakalı",
                "category_id" => 1,
                "author" => "Jose Mauro De Vasconcelos",
                "list_price" => 33,
                "stock_quantity" => 1,
                "store_id" => 1,
                "store_name" => "i&d",
                "images" => null
            ],
            [
                "title" => "Sen Yola Çık Yol Sana Görünür",
                "category_id" => 2,
                "author" => "Hakan Mengüç",
                "list_price" => 28.5,
                "stock_quantity" => 7,
                "store_id" => 1,
                "store_name" => "i&d",
                "sold_quantity" => 0,
                "images" => null
            ],
            [
                "title" => "Kara Delikler",
                "category_id" => 3,
                "author" => "Stephen Hawking",
                "list_price" => 39,
                "stock_quantity" => 2,
                "store_id" => 1,
                "store_name" => "i&d",
                "sold_quantity" => 0,
                "images" => null
            ],
            [
                "title" => "Allah De Ötesini Bırak",
                "category_id" => 4,
                "author" => "Uğur Koşar",
                "list_price" => 39.6,
                "stock_quantity" => 18,
                "store_id" => 1,
                "store_name" => "i&d",
                "sold_quantity" => 0,
                "images" => null
            ],
            [
                "title" => "Aşk 5 Vakittir",
                "category_id" => 4,
                "author" => "Mehmet Yıldız",
                "list_price" => 42,
                "stock_quantity" => 9,
                "store_id" => 1,
                "store_name" => "i&d",
                "sold_quantity" => 0,
                "images" => null
            ],
            [
                "title" => "Benim Zürafam Uçabilir",
                "category_id" => 7,
                "author" => "Mert Arık",
                "list_price" => 27.3,
                "stock_quantity" => 12,
                "store_id" => 1,
                "store_name" => "i&d",
                "sold_quantity" => 0,
                "images" => null
            ],
            [
                "title" => "Kuyucaklı Yusuf",
                "category_id" => 1,
                "author" => "Sabahattin Ali",
                "list_price" => 10.4,
                "stock_quantity" => 2,
                "store_id" => 1,
                "store_name" => "i&d",
                "sold_quantity" => 0,
                "images" => null
            ],
            [
                "title" => "Kamyon - Seçme Öyküler",
                "category_id" => 5,
                "author" => "Sabahattin Ali",
                "list_price" => 9.75,
                "stock_quantity" => 9,
                "store_id" => 1,
                "store_name" => "i&d",
                "sold_quantity" => 0,
                "images" => null
            ],
            [
                "title" => "Kendime Düşünceler",
                "category_id" => 6,
                "author" => "Marcus Aurelius",
                "list_price" => 14.40,
                "stock_quantity" => 1,
                "store_id" => 1,
                "store_name" => "i&d",
                "sold_quantity" => 0,
                "images" => null
            ],
            [
                "title" => "Denemeler - Hasan Ali Yücel Klasikleri",
                "category_id" => 6,
                "author" => "Michel de Montaigne",
                "list_price" => 24,
                "stock_quantity" => 4,
                "store_id" => 1,
                "store_name" => "i&d",
                "sold_quantity" => 0,
                "images" => null
            ],
            [
                "title" => "Animal Farm",
                "category_id" => 1,
                "author" => "George Orwell",
                "list_price" => 17.50,
                "stock_quantity" => 1,
                "store_id" => 1,
                "store_name" => "i&d",
                "sold_quantity" => 0,
                "images" => null
            ],
            [
                "title" => "Dokuzuncu Hariciye Koğuşu",
                "category_id" => 1,
                "author" => "Peyami Safa",
                "list_price" => 18.5,
                "stock_quantity" => 0,
                "store_id" => 1,
                "store_name" => "i&d",
                "sold_quantity" => 0,
                "images" => null
            ],
            // Ahmet Kitapçı'nın ürünleri (store_id: 2)
            [
                "title" => "Suç ve Ceza",
                "category_id" => 1,
                "author" => "Dostoyevski",
                "list_price" => 75.50,
                "stock_quantity" => 25,
                "store_id" => 2,
                "store_name" => "Ahmet'in Kitap Dünyası",
                "sold_quantity" => 0,
                "images" => null
            ],
            [
                "title" => "Beyaz Geceler",
                "category_id" => 1,
                "author" => "Dostoyevski",
                "list_price" => 45.00,
                "stock_quantity" => 15,
                "store_id" => 2,
                "store_name" => "Ahmet'in Kitap Dünyası",
                "sold_quantity" => 0,
                "images" => null
            ],
            [
                "title" => "Karamazov Kardeşler",
                "category_id" => 1,
                "author" => "Dostoyevski",
                "list_price" => 120.00,
                "stock_quantity" => 8,
                "store_id" => 2,
                "store_name" => "Ahmet'in Kitap Dünyası",
                "sold_quantity" => 0,
                "images" => null
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
