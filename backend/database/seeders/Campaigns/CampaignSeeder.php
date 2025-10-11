<?php

namespace Database\Seeders\Campaigns;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\Models\Campaign;

class CampaignSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        Campaign::insert([
            [
                'name'           => 'Kışa Özel %20',
                'store_id'       => 1,
                'code'           => null,
                'type'           => 'percentage',
                'description'    => 'Seçili Ürünlerde Kışa Özel %20',
                'discount_value' => 20,
                'buy_quantity'   => null,
                'pay_quantity'   => null,
                'min_subtotal'   => null,
                'usage_limit'    => 500,
                'usage_count'    => 0,
                'is_active'      => true,
                'starts_at'      => $now->copy()->subWeek(),
                'ends_at'        => $now->copy()->addMonth(),
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'name'           => '250 TL Sepet İndirimi',
                'store_id'       => 1,
                'code'           => null,
                'type'           => 'fixed',
                'description'    => 'Seçili Ürünlerde 250 TL Sepet İndirimi',
                'discount_value' => 250,
                'buy_quantity'   => null,
                'pay_quantity'   => null,
                'min_subtotal'   => null,
                'usage_limit'    => 200,
                'usage_count'    => 0,
                'is_active'      => true,
                'starts_at'      => $now,
                'ends_at'        => $now->copy()->addMonths(2),
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'name'           => '3 Al 2 Öde',
                'store_id'       => 1,
                'code'           => null,
                'type'           => 'x_buy_y_pay',
                'description'    => 'Seçili Ürünlerde 3 Al 2 Öde',
                'discount_value' => null,
                'buy_quantity'   => 3,
                'pay_quantity'   => 2,
                'min_subtotal'   => null,
                'usage_limit'    => null,
                'usage_count'    => 0,
                'is_active'      => true,
                'starts_at'      => $now,
                'ends_at'        => null,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
        ]);
    }
}
