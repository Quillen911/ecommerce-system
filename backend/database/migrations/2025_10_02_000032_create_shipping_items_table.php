<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('shipping_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_item_id')->constrained('order_items')->onDelete('cascade');
            $table->string('tracking_number')->nullable();
            $table->string('shipping_company')->nullable(); 
            $table->enum('shipping_status', ['pending', 'preparing', 'shipped', 'in_transit', 'delivered', 'failed'])->default('pending');
            $table->date('estimated_delivery_date')->nullable();
            $table->text('shipping_notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['order_item_id', 'shipping_status']);
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('shipping_items');
    }
};
