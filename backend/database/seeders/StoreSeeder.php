<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Store;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Store::firstOrCreate(
            ['seller_id' => 1, 'name' => 'i&d'],
            [
                "seller_name" => "İsmail",
                "phone" => "532 123 45 67",
                "address" => "İstanbul, Türkiye",
                "image" => null,
                "description" => "i&d kitapları",
                "email" => "danisismail001@i&d.com",
                "is_active" => true
            ]
        );

        Store::firstOrCreate(
            ['seller_id' => 2, 'name' => 'Ahmet\'in Kitap Dünyası'],
            [
                "seller_name" => "Ahmet Kitapçı",
                "phone" => "555 987 65 43",
                "address" => "Kadıköy, İstanbul",
                "image" => null,
                "description" => "Kaliteli kitaplar, uygun fiyatlar",
                "email" => "ahmet@kitapci.com",
                "is_active" => true
            ]
        );
    }
}
