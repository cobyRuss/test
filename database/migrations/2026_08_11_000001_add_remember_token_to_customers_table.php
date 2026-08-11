<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('customers') && ! Schema::hasColumn('customers', 'remember_token')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->string('remember_token', 100)->nullable()->after('password_hash');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('customers') && Schema::hasColumn('customers', 'remember_token')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->dropColumn('remember_token');
            });
        }
    }
};
