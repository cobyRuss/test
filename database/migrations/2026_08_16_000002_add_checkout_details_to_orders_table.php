<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('orders', 'sender_phone')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('sender_phone', 20)->nullable()->after('customer_id');
                $table->string('recipient_name', 255)->nullable()->after('sender_phone');
                $table->string('recipient_phone', 20)->nullable()->after('recipient_name');
                $table->string('recipient_barangay', 255)->nullable()->after('municipality');
                $table->string('recipient_street', 255)->nullable()->after('recipient_barangay');
                $table->text('message_for_recipient')->nullable()->after('special_instructions');
                $table->boolean('sender_anonymous')->default(false)->after('message_for_recipient');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('orders', 'sender_phone')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn([
                    'sender_phone',
                    'recipient_name',
                    'recipient_phone',
                    'recipient_barangay',
                    'recipient_street',
                    'message_for_recipient',
                    'sender_anonymous',
                ]);
            });
        }
    }
};
