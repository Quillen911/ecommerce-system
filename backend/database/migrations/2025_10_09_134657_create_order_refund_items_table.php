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
        Schema::create('order_refund_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_refund_id');
            $table->unsignedBigInteger('order_item_id');
            $table->integer('quantity');
            $table->string('byWho', 10)->default('user');
            $table->bigInteger('refund_amount_cents')->default(0);
            $table->text('reason')->nullable();
            $table->enum('inspection_status', ['pending','approved','rejected'])->default('pending');
            $table->text('inspection_note')->nullable();
            $table->timestamps();

            $table->foreign('order_refund_id')->references('id')->on('order_refunds')->onDelete('cascade');
            $table->foreign('order_item_id')->references('id')->on('order_items')->onDelete('cascade');
            $table->index(['order_refund_id', 'order_item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_refund_items');
    }
};
