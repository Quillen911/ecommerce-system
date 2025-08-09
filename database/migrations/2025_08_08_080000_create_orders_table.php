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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('Bag_User_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('credit_card_id')->nullable();
            $table->string('card_holder_name')->nullable();
            $table->decimal('price', 8, 2);
            $table->decimal('cargo_price', 8, 2);
            $table->decimal('discount', 8, 2)->nullable();
            $table->decimal('campaing_price', 8, 2);
            $table->decimal('paid_price', 10, 2)->nullable();
            $table->string('currency', 3)->default('TRY');
            $table->string('payment_id')->nullable();
            $table->string('conversation_id')->nullable();
            $table->string('status');
            $table->enum('payment_status', ['pending','paid','failed','canceled','refunded','partial_refunded'])->default('pending');
            $table->unsignedBigInteger('campaign_id')->nullable();
            $table->string('campaign_info')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('payment_id');
            $table->foreign('Bag_User_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('credit_card_id')->references('id')->on('credit_cards')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
