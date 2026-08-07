<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('gcash_payments')) {
            Schema::create('gcash_payments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('order_id');
                $table->string('reference_number');
                $table->decimal('amount', 10, 2);
                $table->enum('payment_type', ['down_payment', 'full_payment'])->default('down_payment');
                $table->string('screenshot_path')->nullable();
                $table->boolean('verified')->default(false);
                $table->unsignedBigInteger('verified_by')->nullable();
                $table->timestamp('verified_at')->nullable();
                $table->timestamp('created_at')->useCurrent();

                $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('gcash_payments');
    }
};
