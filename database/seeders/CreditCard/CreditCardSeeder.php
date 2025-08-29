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
            'last_four_digits' => '0008', //  5528790000000008
            'expire_month' => '12',
            'expire_year' => '2030',
            'card_type' => 'Mastercard',
            'card_holder_name' => 'Geçerli Kart',
            'is_active' => 1,
        ]);
        CreditCard::create([
            'user_id' => 1,
            'name' => 'İsmail Danış Test',
            'last_four_digits' => '1111',
            'expire_month' => '12',
            'expire_year' => '2030',
            'card_type' => 'Visa',
            'card_holder_name' => 'Test Kart',
            'is_active' => 1,
        ]);

        CreditCard::create([
            'user_id' => 2,
            'name' => 'İsmail Danış',
            'last_four_digits' => '0008',
            'expire_month' => '12',
            'expire_year' => '2030',
            'card_type' => 'Mastercard',
            'card_holder_name' => 'Geçerli Kart',
            'is_active' => 1,
        ]);
    }
}
