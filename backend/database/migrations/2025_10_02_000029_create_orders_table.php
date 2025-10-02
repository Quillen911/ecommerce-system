<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
   
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('bag_id')->nullable();
            
            $table->unsignedBigInteger('user_shipping_address_id')->nullable();
            $table->unsignedBigInteger('user_billing_address_id')->nullable();
            
            $table->unsignedBigInteger('campaign_id')->nullable();
            $table->string('campaign_info')->nullable();
            
            $table->string('order_number')->unique();
            
            $table->bigInteger('subtotal_cents')->default(0);        // Ürün toplamı (indirim/vergiden önce)
            $table->bigInteger('discount_cents')->default(0);        // Toplam indirim
            $table->bigInteger('tax_total_cents')->default(0);       // Toplam vergi
            $table->bigInteger('cargo_price_cents')->default(0);     // Kargo ücreti
            $table->bigInteger('campaign_price_cents')->default(0);  // Kampanya indirimi
            $table->bigInteger('grand_total_cents')->default(0);     // NET Ödenecek: subtotal - discount + tax + cargo
            
            $table->string('currency', 3)->default('TRY');
            
            $table->enum('status', [
                'pending', 'confirmed', 'shipped', 'delivered',
                'partial_refunded', 'refunded', 'cancelled', 'failed'
            ])->default('pending');
            
            $table->timestamp('refunded_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('bag_id')->references('id')->on('bags')->nullOnDelete();
            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
            $table->foreign('user_shipping_address_id')->references('id')->on('user_addresses')->onDelete('cascade');
            $table->foreign('user_billing_address_id')->references('id')->on('user_addresses')->onDelete('cascade');
            
            $table->index(['user_id', 'status', 'created_at']); // Kullanıcı sipariş listesi
            $table->index(['order_number']); // Sipariş arama
            $table->index(['status', 'created_at']); // Admin liste
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};