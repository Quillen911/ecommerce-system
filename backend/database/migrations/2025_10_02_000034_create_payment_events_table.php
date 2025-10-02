<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('payment_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained('payments')->onDelete('cascade');
            $table->string('provider');
            $table->string('event_type');
            $table->string('event_id')->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();

            $table->index(['payment_id','event_type','created_at']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('payment_events');
    }
};
