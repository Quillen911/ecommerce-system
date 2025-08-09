<?php

namespace Database\Seeders\CreditCard;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CreditCard;

class CreditCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CreditCard::create([
            'user_id' => 1,
            'name' => 'İsmail Danış',
            'card_number' => '5528790000000008',
            'expire_month' => '12',
            'expire_year' => '2030',
            'card_type' => 'Mastercard',
            'card_holder_name' => 'Geçerli Kart',
            'cvv' => '123',
            'is_active' => 1,
        ]);
        CreditCard::create([
            'user_id' => 1,
            'name' => 'İsmail Danış',
            'card_number' => '1111111111111111',
            'expire_month' => '12',
            'expire_year' => '2030',
            'card_type' => 'Visa',
            'card_holder_name' => 'Geçersiz Kart',
            'cvv' => '123',
            'is_active' => 1,
        ]);
    }
}
