<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Orders tablosu - kuruş alanları ekle
        Schema::table('orders', function (Blueprint $table) {
            $table->bigInteger('order_price_cents')->default(0)->after('order_price');
            $table->bigInteger('cargo_price_cents')->default(0)->after('cargo_price');
            $table->bigInteger('discount_cents')->default(0)->after('discount');
            $table->bigInteger('campaign_price_cents')->default(0)->after('campaign_price');
            $table->bigInteger('paid_price_cents')->default(0)->after('paid_price');
        });

        // Order_items tablosu - kuruş alanları ekle
        Schema::table('order_items', function (Blueprint $table) {
            $table->bigInteger('list_price_cents')->default(0)->after('list_price');
            $table->bigInteger('paid_price_cents')->default(0)->after('paid_price');
            $table->bigInteger('refunded_price_cents')->default(0)->after('refunded_price');
            $table->integer('refunded_quantity')->default(0)->after('quantity');
        });

        // Products tablosu - kuruş alanları ekle
        Schema::table('products', function (Blueprint $table) {
            $table->bigInteger('list_price_cents')->default(0)->after('list_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Orders tablosu - kuruş alanları kaldır
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'order_price_cents',
                'cargo_price_cents', 
                'discount_cents',
                'campaign_price_cents',
                'paid_price_cents'
            ]);
        });

        // Order_items tablosu - kuruş alanları kaldır
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn([
                'list_price_cents',
                'paid_price_cents',
                'refunded_price_cents',
                'refunded_quantity'
            ]);
        });

        // Products tablosu - kuruş alanları kaldır
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('list_price_cents');
        });
    }
};
