<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->string('order_number')->unique();
                $table->unsignedBigInteger('customer_id');
                $table->decimal('total_amount', 10, 2);
                $table->decimal('delivery_fee', 10, 2)->default(0);
                $table->decimal('down_payment', 10, 2)->default(0);
                $table->decimal('remaining_balance', 10, 2)->default(0);
                $table->enum('payment_method', ['gcash', 'cod']);
                $table->enum('payment_status', ['pending_downpayment', 'partial', 'completed', 'pending_cod'])->default('pending_downpayment');
                $table->enum('order_status', ['pending', 'confirmed', 'preparing', 'ready', 'delivered', 'cancelled'])->default('pending');
                $table->text('delivery_address');
                $table->string('municipality');
                $table->date('delivery_date');
                $table->text('special_instructions')->nullable();
                $table->string('cancel_reason')->nullable();
                $table->text('cancel_note')->nullable();
                $table->timestamp('cancelled_at')->nullable();
                $table->timestamps();

                $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
