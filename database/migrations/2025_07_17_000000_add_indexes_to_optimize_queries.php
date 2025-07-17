<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bags', function (Blueprint $table) {
            $table->index('Bag_User_id');
        });
        Schema::table('bag_items', function (Blueprint $table) {
            $table->index('bagItem_id');
            $table->index('product_id');
        });
        Schema::table('products', function (Blueprint $table) {
            $table->index('id');
        });
    }

    public function down(): void
    {
        Schema::table('bags', function (Blueprint $table) {
            $table->dropIndex(['Bag_User_id']);
        });
        Schema::table('bag_items', function (Blueprint $table) {
            $table->dropIndex(['bagItem_id']);
            $table->dropIndex(['product_id']);
        });
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['id']);
        });
    }
}; 