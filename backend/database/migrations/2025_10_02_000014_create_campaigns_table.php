<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->unique()->nullable();
            $table->text('description')->nullable();
            $table->enum('type', ['percentage', 'fixed', 'x_buy_y_pay']);
            $table->decimal('discount_value', 10, 2)->nullable();
            $table->unsignedInteger('buy_quantity')->nullable();
            $table->unsignedInteger('pay_quantity')->nullable();
            $table->integer('min_subtotal')->nullable();
            $table->integer('usage_limit')->nullable();
            $table->integer('per_user_limit')->nullable();
            $table->integer('usage_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
