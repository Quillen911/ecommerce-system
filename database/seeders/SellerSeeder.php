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
        Seller::create([
            'name' => 'İsmail',
            'email' => 'danisismail001@gmail.com',
            'password' => Hash::make('ismail'),
            'role' => 'seller',
            'status' => true,
        ]);
    }
}
