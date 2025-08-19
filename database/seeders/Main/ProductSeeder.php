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
                "list_price" => 259.90,
                "list_price_cents" => 25990,
                "stock_quantity" => 10,
                "store_id" => 1,
                "store_name" => "i&d",
                "sold_quantity" => 0,
                "images" => ["ince-memed.png"]
            ],
            [
                "title" => "Tutunamayanlar",
                "category_id" => 1,
                "author" => "Oğuz Atay",
                "list_price" => 339.50,
                "list_price_cents" => 33950,
                "stock_quantity" => 20,
                "store_id" => 1,
                "store_name" => "i&d",
                "sold_quantity" => 0,
                "images" => ["tutunamayanlar.png"]
            ],
            [
                "title" => "Kürk Mantolu Madonna",
                "category_id" => 1,
                "author" => "Sabahattin Ali",
                "list_price" => 169.90,
                "list_price_cents" => 16990,
                "stock_quantity" => 4,
                "store_id" => 1,
                "store_name" => "i&d",
                "sold_quantity" => 0,
                "images" => ["kurk-mantolu-madonna.png"]
            ],
            [
                "title" => "Fareler ve İnsanlar",
                "category_id" => 1,
                "author" => "John Steinbeck",
                "list_price" => 175.50,
                "list_price_cents" => 17550,
                "stock_quantity" => 8,
                "store_id" => 1,
                "store_name" => "i&d",
                "sold_quantity" => 0,
                "images" => ["fareler-ve-insanlar.png"]
            ],
            [
                "title" => "Şeker Portakalı",
                "category_id" => 1,
                "author" => "Jose Mauro De Vasconcelos",
                "list_price" => 149.90,
                "list_price_cents" => 14990,
                "stock_quantity" => 1,
                "store_id" => 1,
                "store_name" => "i&d",
                "images" => ["seker-portakali.png"]
            ],
            [
                "title" => "Sen Yola Çık Yol Sana Görünür",
                "category_id" => 2,
                "author" => "Hakan Mengüç",
                "list_price" => 184.50,
                "list_price_cents" => 18450,
                "stock_quantity" => 7,
                "store_id" => 1,
                "store_name" => "i&d",
                "sold_quantity" => 0,
                "images" => ["sen-yola-cik-yol-sana-gorunur.png"]
            ],
            [
                "title" => "Kara Delikler",
                "category_id" => 3,
                "author" => "Stephen Hawking",
                "list_price" => 219.90,
                "list_price_cents" => 21990,
                "stock_quantity" => 2,
                "store_id" => 1,
                "store_name" => "i&d",
                "sold_quantity" => 0,
                "images" => ["kara-delikler.png"]
            ],
            [
                "title" => "Allah De Ötesini Bırak",
                "category_id" => 4,
                "author" => "Uğur Koşar",
                "list_price" => 157.50,
                "list_price_cents" => 15750,
                "stock_quantity" => 18,
                "store_id" => 1,
                "store_name" => "i&d",
                "sold_quantity" => 0,
                "images" => ["allah-de-otesini-birak.png"]
            ],
            [
                "title" => "Aşk 5 Vakittir",
                "category_id" => 4,
                "author" => "Mehmet Yıldız",
                "list_price" => 159.50,
                "list_price_cents" => 15950,
                "stock_quantity" => 9,
                "store_id" => 1,
                "store_name" => "i&d",
                "sold_quantity" => 0,
                "images" => ["ask-5-vakittir.png"]
            ],
            [
                "title" => "Benim Zürafam Uçabilir",
                "category_id" => 7,
                "author" => "Mert Arık",
                "list_price" => 129.90,
                "list_price_cents" => 12990,
                "stock_quantity" => 12,
                "store_id" => 1,
                "store_name" => "i&d",
                "sold_quantity" => 0,
                "images" => ["benim-zurafam-ucabilir.png"]
            ],
            [
                "title" => "Kuyucaklı Yusuf",
                "category_id" => 1,
                "author" => "Sabahattin Ali",
                "list_price" => 179.90,
                "list_price_cents" => 17990,
                "stock_quantity" => 2,
                "store_id" => 1,
                "store_name" => "i&d",
                "sold_quantity" => 0,
                "images" => ["kuyucakli-yusuf.png"]
            ],
            [
                "title" => "Kamyon - Seçme Öyküler",
                "category_id" => 5,
                "author" => "Sabahattin Ali",
                "list_price" => 169.50,
                "list_price_cents" => 16950,
                "stock_quantity" => 9,
                "store_id" => 1,
                "store_name" => "i&d",
                "sold_quantity" => 0,
                "images" => ["kamyon-secme-oykuler.png"]
            ],
            [
                "title" => "Kendime Düşünceler",
                "category_id" => 6,
                "author" => "Marcus Aurelius",
                "list_price" => 214.90,
                "list_price_cents" => 21490,
                "stock_quantity" => 1,
                "store_id" => 1,
                "store_name" => "i&d",
                "sold_quantity" => 0,
                "images" => ["kendime-dusunceler.png"]
            ],
            [
                "title" => "Denemeler - Hasan Ali Yücel Klasikleri",
                "category_id" => 6,
                "author" => "Michel de Montaigne",
                "list_price" => 229.90,
                "list_price_cents" => 22990,
                "stock_quantity" => 4,
                "store_id" => 1,
                "store_name" => "i&d",
                "sold_quantity" => 0,
                "images" => ["denemeler-montaigne.png"]
            ],
            [
                "title" => "Animal Farm",
                "category_id" => 1,
                "author" => "George Orwell",
                "list_price" => 189.90,
                "list_price_cents" => 18990,
                "stock_quantity" => 1,
                "store_id" => 1,
                "store_name" => "i&d",
                "sold_quantity" => 0,
                "images" => ["animal-farm.png"]
            ],
            [
                "title" => "Dokuzuncu Hariciye Koğuşu",
                "category_id" => 1,
                "author" => "Peyami Safa",
                "list_price" => 174.50,
                "list_price_cents" => 17450,
                "stock_quantity" => 0,
                "store_id" => 1,
                "store_name" => "i&d",
                "sold_quantity" => 0,
                "images" => ["dokuzuncu-hariciye-kogusu.png"]
            ],
            // Ahmet Kitapçı'nın ürünleri (store_id: 2)
            [
                "title" => "Suç ve Ceza",
                "category_id" => 1,
                "author" => "Dostoyevski",
                "list_price" => 246.50,
                "list_price_cents" => 24650,
                "stock_quantity" => 25,
                "store_id" => 2,
                "store_name" => "Ahmet'in Kitap Dünyası",
                "sold_quantity" => 0,
                "images" => ["suc-ve-ceza.png"]
            ],
            [
                "title" => "Beyaz Geceler",
                "category_id" => 1,
                "author" => "Dostoyevski",
                "list_price" => 129.50,
                "list_price_cents" => 12950,
                "stock_quantity" => 15,
                "store_id" => 2,
                "store_name" => "Ahmet'in Kitap Dünyası",
                "sold_quantity" => 0,
                "images" => ["beyaz-geceler.png"]
            ],
            [
                "title" => "Karamazov Kardeşler",
                "category_id" => 1,
                "author" => "Dostoyevski",
                "list_price" => 294.50,
                "list_price_cents" => 29450,
                "stock_quantity" => 8,
                "store_id" => 2,
                "store_name" => "Ahmet'in Kitap Dünyası",
                "sold_quantity" => 0,
                "images" => ["karamazov-kardesler.png"]
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
