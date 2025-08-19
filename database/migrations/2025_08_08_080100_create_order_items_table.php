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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('store_id');
            $table->string('store_name');
            $table->string('product_title')->nullable();
            $table->string('product_category_title')->nullable();
            $table->integer('quantity');
            $table->integer('refunded_quantity')->default(0);
            $table->decimal('list_price', 10, 2);
            $table->bigInteger('list_price_cents')->default(0);
            $table->decimal('paid_price', 10, 2)->default(0);
            $table->bigInteger('paid_price_cents')->default(0);
            $table->string('payment_transaction_id')->nullable();
            $table->enum('status', ['pending','confirmed','shipped','delivered','refunded','Müşteri İade Etti','Başarısız Ödeme']);
            $table->decimal('refunded_price', 10, 2)->default(0);
            $table->bigInteger('refunded_price_cents')->default(0);
            $table->enum('payment_status', ['paid','partial_refunded','refunded','failed','canceled']);
            $table->timestamp('refunded_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

           
            $table->index('payment_transaction_id');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
