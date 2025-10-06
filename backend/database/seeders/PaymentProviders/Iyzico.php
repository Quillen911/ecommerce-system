<?php

namespace Database\Seeders\PaymentProviders;

use Illuminate\Database\Seeder;

use App\Models\PaymentProvider;

class Iyzico extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentProvider::updateOrCreate(
            ['code' => 'iyzico'],
            [
                'name' => 'Iyzico',
                'is_active' => true,
                'config' => [
                    'api_key' => encrypt(env('IYZICO_API_KEY', '')),
                    'secret_key' => encrypt(env('IYZICO_SECRET_KEY', '')),
                    'base_url' => env('IYZICO_BASE_URL', 'https://sandbox-api.iyzipay.com'),
                ],
            ]
        );
    }
}