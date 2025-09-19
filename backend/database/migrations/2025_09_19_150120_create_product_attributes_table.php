<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('attribute_id')->constrained('attributes')->onDelete('cascade');
            $table->foreignId('option_id')->nullable()->constrained('attribute_options')->nullOnDelete();
            $table->text('value')->nullable();
            $table->decimal('value_number', 12, 2)->nullable();
            $table->boolean('value_bool')->nullable();
            $table->timestamps();

            $table->index(['product_id', 'attribute_id']);
            $table->index(['option_id']);
            $table->index(['value_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_attributes');
    }
};


