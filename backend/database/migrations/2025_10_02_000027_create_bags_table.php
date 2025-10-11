<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('bags', function (Blueprint $table) {
            $table->id();   
            $table->unsignedBigInteger('bag_user_id')->nullable();
            $table->foreignId('campaign_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('campaign_discount_cents')->default(0);
            $table->timestamps();

            $table->foreign('bag_user_id')->references('id')->on('users')->onDelete('cascade');
        });

       

    }

    public function down(): void
    {
        Schema::dropIfExists('bags');
    }
};
