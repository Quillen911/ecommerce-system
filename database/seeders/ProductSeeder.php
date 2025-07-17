<?php

namespace Database\Seeders;

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
                "stock_quantity" => 10
            ],
            [
                "title" => "Tutunamayanlar",
                "category_id" => 1,
                "author" => "Oğuz Atay",
                "list_price" => 90.3,
                "stock_quantity" => 20
            ],
            [
                "title" => "Kürk Mantolu Madonna",
                "category_id" => 1,
                "author" => "Sabahattin Ali",
                "list_price" => 9.1,
                "stock_quantity" => 4
            ],
            [
                "title" => "Fareler ve İnsanlar",
                "category_id" => 1,
                "author" => "John Steinback",
                "list_price" => 35.75,
                "stock_quantity" => 8
            ],
            [
                "title" => "Şeker Portakalı",
                "category_id" => 1,
                "author" => "Jose Mauro De Vasconcelos",
                "list_price" => 33,
                "stock_quantity" => 1
            ],
            [
                "title" => "Sen Yola Çık Yol Sana Görünür",
                "category_id" => 2,
                "author" => "Hakan Mengüç",
                "list_price" => 28.5,
                "stock_quantity" => 7
            ],
            [
                "title" => "Kara Delikler",
                "category_id" => 3,
                "author" => "Stephen Hawking",
                "list_price" => 39,
                "stock_quantity" => 2
            ],
            [
                "title" => "Allah De Ötesini Bırak",
                "category_id" => 4,
                "author" => "Uğur Koşar",
                "list_price" => 39.6,
                "stock_quantity" => 18
            ],
            [
                "title" => "Aşk 5 Vakittir",
                "category_id" => 4,
                "author" => "Mehmet Yıldız",
                "list_price" => 42,
                "stock_quantity" => 9
            ],
            [
                "title" => "Benim Zürafam Uçabilir",
                "category_id" => 7,
                "author" => "Mert Arık",
                "list_price" => 27.3,
                "stock_quantity" => 12
            ],
            [
                "title" => "Kuyucaklı Yusuf",
                "category_id" => 1,
                "author" => "Sabahattin Ali",
                "list_price" => 10.4,
                "stock_quantity" => 2
            ],
            [
                "title" => "Kamyon - Seçme Öyküler",
                "category_id" => 5,
                "author" => "Sabahattin Ali",
                "list_price" => 9.75,
                "stock_quantity" => 9
            ],
            [
                "title" => "Kendime Düşünceler",
                "category_id" => 6,
                "author" => "Marcus Aurelius",
                "list_price" => 14.40,
                "stock_quantity" => 1
            ],
            [
                "title" => "Denemeler - Hasan Ali Yücel Klasikleri",
                "category_id" => 6,
                "author" => "Michel de Montaigne",
                "list_price" => 24,
                "stock_quantity" => 4
            ],
            [
                "title" => "Animal Farm",
                "category_id" => 1,
                "author" => "George Orwell",
                "list_price" => 17.50,
                "stock_quantity" => 1
            ],
            [
                "title" => "Dokuzuncu Hariciye Koğuşu",
                "category_id" => 1,
                "author" => "Peyami Safa",
                "list_price" => 18.5,
                "stock_quantity" => 0
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
