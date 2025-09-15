<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Seller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SellerSeeder extends Seeder
{
   
    public function run(): void
    {
        Seller::firstOrCreate(
            ['email' => 'danisismail001@gmail.com'],
            [
                'name' => 'İsmail',
                'password' => Hash::make('ismail'),
                'role' => 'seller',
                'status' => true,
            ]
        );

        Seller::firstOrCreate(
            ['email' => 'ahmet@kitapci.com'],
            [
                'name' => 'Ahmet Kitapçı',
                'password' => Hash::make('ahmet123'),
                'role' => 'seller',
                'status' => true,
            ]
        );
    }
}
