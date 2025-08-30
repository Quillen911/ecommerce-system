<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('credit_cards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('last_four_digits', 4); //5528790000000008             
            $table->string('expire_year');
            $table->string('expire_month');
            $table->string('card_type');
            $table->string('card_holder_name');
            $table->boolean('is_active')->default(true);
            $table->text('iyzico_card_token')->nullable();       
            $table->text('iyzico_card_user_key')->nullable();    
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('credit_cards');
    }
};
