<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gcash_payments', function (Blueprint $table) {
            $table->enum('status', ['pending', 'verified', 'declined'])->default('pending')->after('verified');
        });

        DB::table('gcash_payments')
            ->where('verified', true)
            ->update(['status' => 'verified']);
    }

    public function down(): void
    {
        Schema::table('gcash_payments', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
