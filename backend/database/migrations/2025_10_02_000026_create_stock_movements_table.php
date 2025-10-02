<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('variant_size_id')->constrained('variant_sizes')->onDelete('cascade');
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('cascade');
            $table->foreignId('inventory_id')->constrained('inventories')->onDelete('cascade');
            $table->enum('type', ['inbound', 'outbound', 'sale', 'return', 'adjustment', 'reserve', 'release']);
            $table->integer('quantity');
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['variant_size_id', 'created_at']);
            $table->index(['warehouse_id', 'created_at']);
            $table->index(['type', 'created_at']);
            $table->index(['inventory_id', 'type']);
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
