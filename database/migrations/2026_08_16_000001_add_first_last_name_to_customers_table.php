<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('customers', 'first_name')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->string('first_name')->nullable()->after('id');
                $table->string('last_name')->nullable()->after('first_name');
                $table->string('phone')->nullable()->change();
                $table->text('address')->nullable()->change();
            });

            foreach (DB::table('customers')->whereNull('first_name')->get() as $customer) {
                $parts = preg_split('/\s+/', trim((string) $customer->full_name), 2);
                DB::table('customers')->where('id', $customer->id)->update([
                    'first_name' => $parts[0] ?? null,
                    'last_name' => $parts[1] ?? null,
                ]);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('customers', 'first_name')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->dropColumn(['first_name', 'last_name']);
                $table->string('phone')->change();
                $table->text('address')->change();
            });
        }
    }
};
