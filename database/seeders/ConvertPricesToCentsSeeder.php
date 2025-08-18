<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConvertPricesToCentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Converting prices to cents...');

        // Orders dönüştürme
        $this->command->info('Converting orders...');
        DB::table('orders')->orderBy('id')->chunk(100, function ($orders) {
            foreach ($orders as $order) {
                DB::table('orders')->where('id', $order->id)->update([
                    'order_price_cents' => (int)($order->order_price * 100),
                    'cargo_price_cents' => (int)($order->cargo_price * 100),
                    'discount_cents' => (int)($order->discount * 100),
                    'campaign_price_cents' => (int)($order->campaign_price * 100),
                    'paid_price_cents' => (int)($order->paid_price * 100),
                ]);
            }
        });

        // Order_items dönüştürme
        $this->command->info('Converting order items...');
        DB::table('order_items')->orderBy('id')->chunk(100, function ($items) {
            foreach ($items as $item) {
                DB::table('order_items')->where('id', $item->id)->update([
                    'list_price_cents' => (int)($item->list_price * 100),
                    'paid_price_cents' => (int)($item->paid_price * 100),
                    'refunded_price_cents' => (int)($item->refunded_price * 100),
                ]);
            }
        });

        // Products dönüştürme
        $this->command->info('Converting products...');
        DB::table('products')->orderBy('id')->chunk(100, function ($products) {
            foreach ($products as $product) {
                DB::table('products')->where('id', $product->id)->update([
                    'list_price_cents' => (int)($product->list_price * 100),
                ]);
            }
        });

        $this->command->info('Price conversion completed successfully!');
    }
}
