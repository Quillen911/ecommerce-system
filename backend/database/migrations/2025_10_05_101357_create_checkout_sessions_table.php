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
        Schema::create('checkout_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();          // session_id
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('bag_id')->nullable()->constrained()->nullOnDelete();


            $table->json('bag_snapshot')->nullable();      // sepetin o anki görüntüsü (ürünler, fiyatlar)
            $table->json('shipping_data')->nullable();     // seçilen adres, kargo servisi, tahmini teslim
            $table->json('billing_data')->nullable();      // fatura adresi, vergi bilgileri
            $table->json('payment_data')->nullable();      // seçilen payment_method, intent id, taksit
            $table->json('meta')->nullable();              // kupon, notlar, KVKK onayı vb.

            $table->enum('status', ['pending','shipping_selected','payment_pending','confirmed','cancelled','expired'])
                ->default('pending');

            $table->timestamp('expires_at')->nullable();   // 15-30 dk gibi TTL
            $table->timestamps();

            $table->index(['user_id','status']);
            $table->index(['expires_at']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkout_sessions');
    }
};
