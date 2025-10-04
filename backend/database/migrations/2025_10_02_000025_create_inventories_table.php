<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('variant_size_id')->constrained('variant_sizes')->onDelete('cascade');
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('cascade')->default(1);
            $table->integer('on_hand')->default(0);
            $table->integer('reserved')->default(0);
            $table->integer('available')->default(0);
            $table->integer('min_stock_level')->default(0);
            $table->timestamps();
            
            $table->unique(['variant_size_id', 'warehouse_id']);
            $table->index(['warehouse_id', 'on_hand']);
            $table->index(['variant_size_id', 'available']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
