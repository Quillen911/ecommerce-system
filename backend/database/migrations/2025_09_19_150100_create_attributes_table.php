<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('code', 120)->unique();
            $table->string('input_type', 20); // text, number, boolean, select
            $table->boolean('is_filterable')->default(true);
            $table->boolean('is_required')->default(false);
            $table->smallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['input_type', 'is_filterable']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attributes');
    }
};


