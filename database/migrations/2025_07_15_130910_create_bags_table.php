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
            $table->unsignedBigInteger('Bag_User_id')->nullable();
            $table->timestamps();

            $table->foreign('Bag_User_id')->references('id')->on('users')->onDelete('cascade');
        });

       

    }

    public function down(): void
    {
        Schema::dropIfExists('bags');
    }
};
