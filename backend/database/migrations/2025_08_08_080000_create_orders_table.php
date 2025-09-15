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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bag_user_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('user_shipping_address_id')->nullable();
            $table->unsignedBigInteger('user_billing_address_id')->nullable();
            $table->unsignedBigInteger('credit_card_id')->nullable();
            $table->string('card_holder_name')->nullable();
            $table->unsignedBigInteger('campaign_id')->nullable();
            $table->string('campaign_info')->nullable();
            $table->decimal('order_price', 10, 2);
            $table->bigInteger('order_price_cents')->default(0);
            $table->decimal('cargo_price', 10, 2);
            $table->bigInteger('cargo_price_cents')->default(0);
            $table->decimal('discount', 10, 2)->nullable();
            $table->bigInteger('discount_cents')->default(0);
            $table->decimal('campaign_price', 10, 2);
            $table->bigInteger('campaign_price_cents')->default(0);
            $table->decimal('paid_price', 10, 2)->nullable();
            $table->bigInteger('paid_price_cents')->default(0);
            $table->string('currency', 3)->default('TRY');
            $table->string('payment_id')->nullable();
            $table->string('conversation_id')->nullable();
            $table->enum('status', ['pending','confirmed','shipped','delivered','Kısmi İade','İade Edildi','cancelled','Başarısız Ödeme']);
            $table->enum('payment_status', ['paid','failed','refunded','partial_refunded','canceled']);
            $table->timestamp('refunded_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('payment_id');
            $table->foreign('bag_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('credit_card_id')->references('id')->on('credit_cards')->onDelete('cascade');
            $table->foreign('user_shipping_address_id')->references('id')->on('user_addresses')->onDelete('cascade');
            $table->foreign('user_billing_address_id')->references('id')->on('user_addresses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
