<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('variant_size_id')->nullable();
            $table->unsignedBigInteger('store_id');
            $table->string('store_name');
            $table->string('product_title')->nullable();
            $table->string('product_category_title')->nullable();
            $table->json('selected_options')->nullable();
            $table->string('size_name')->nullable();
            $table->string('color_name')->nullable();
            $table->integer('quantity');
            $table->integer('refunded_quantity')->default(0);
            $table->bigInteger('price_cents')->default(0);
            $table->bigInteger('discount_price_cents')->default(0);
            $table->bigInteger('paid_price_cents')->default(0);
            $table->integer('tax_rate')->default(1800);
            $table->bigInteger('tax_amount_cents')->default(0);
            $table->string('payment_transaction_id')->nullable();
            $table->enum('status', ['pending','confirmed','shipped','delivered','refunded','Müşteri İade Etti','Satıcı İade Etti','Başarısız Ödeme']);
            $table->bigInteger('refunded_price_cents')->default(0);
            $table->enum('payment_status', ['paid','partial_refunded','refunded','failed','canceled']);
            $table->timestamp('refunded_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

           
            $table->index('payment_transaction_id');
            $table->index(['order_id']);
            $table->index(['product_id', 'created_at']);

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('variant_size_id')->references('id')->on('variant_sizes')->onDelete('cascade');
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
