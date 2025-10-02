<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('payment_method_id')->nullable()
                  ->constrained('payment_methods')->nullOnDelete();

            $table->string('provider')->default('iyzico');
            $table->string('provider_payment_id')->nullable();
            $table->string('conversation_id')->nullable();

            $table->bigInteger('amount_cents')->default(0);
            $table->bigInteger('authorized_amount_cents')->default(0);
            $table->bigInteger('captured_amount_cents')->default(0);
            $table->bigInteger('refunded_amount_cents')->default(0);
            $table->string('currency', 3)->default('TRY');

            $table->enum('status', [
                'pending','authorized','captured','failed','refunded','partial_refunded','canceled'
            ])->default('pending');

            $table->unsignedTinyInteger('installment_count')->default(1);
            $table->bigInteger('installment_commission_cents')->default(0);

            $table->string('three_ds_status')->nullable();
            $table->string('error_code')->nullable();
            $table->text('error_message')->nullable();

            $table->timestamp('authorized_at')->nullable();
            $table->timestamp('captured_at')->nullable();
            $table->timestamp('refunded_at')->nullable();

            $table->json('payload')->nullable();

            $table->timestamps();

            $table->index(['order_id','status']);
            $table->index(['payment_method_id']);
            $table->index(['provider_payment_id']);
            $table->index(['conversation_id']);
            $table->index(['created_at','status']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('payments');
    }
};
