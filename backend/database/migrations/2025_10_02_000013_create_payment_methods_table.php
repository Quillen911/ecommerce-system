<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('provider');
            $table->string('provider_customer_id');
            $table->string('provider_payment_method_id')->nullable();
            $table->enum('type', ['card','wallet','bank_transfer','bnpl','other'])->default('card');

            $table->string('brand')->nullable();
            $table->char('last4', 4)->nullable();
            $table->string('fingerprint')->nullable();

            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);

            $table->foreignId('billing_address_id')->nullable()
                  ->constrained('user_addresses')->nullOnDelete();

            $table->json('metadata')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['provider','provider_payment_method_id'], 'uniq_provider_pm');
            $table->index(['user_id','provider','is_active']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('payment_methods');
    }
};
