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
        Schema::create('bag_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bag_id');
            $table->unsignedBigInteger('product_id');
            $table->string('product_title');
            $table->string('author');
            $table->integer('quantity')->default(1);
            $table->unsignedBigInteger('store_id');
            $table->timestamps();
        
            $table->foreign('bag_id')->references('id')->on('bags')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bag_items');
    }
};
