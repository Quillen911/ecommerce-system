<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->onDelete('cascade');
            $table->string('store_name');
            $table->string('title');
            $table->string('slug');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->text('description')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->decimal('list_price',10,2);
            $table->bigInteger('list_price_cents')->default(0);
            $table->integer('stock_quantity')->nullable();
            $table->boolean('is_published')->default(true);
            $table->integer('sold_quantity')->default(0);
            $table->json('images')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('cascade');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
