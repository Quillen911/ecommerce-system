<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('bag_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bag_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('variant_size_id')->nullable();
            $table->string('product_title');
            $table->integer('quantity')->default(1);
            $table->bigInteger('unit_price_cents')->default(0);
            $table->unsignedBigInteger('store_id');
            $table->timestamps();
        
            $table->foreign('bag_id')->references('id')->on('bags')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('variant_size_id')->references('id')->on('variant_sizes')->onDelete('cascade');

            $table->index(['bag_id']);
            $table->index(['variant_size_id']);
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('bag_items');
    }
};
