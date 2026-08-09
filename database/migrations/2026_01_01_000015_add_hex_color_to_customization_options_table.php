<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customization_options', function (Blueprint $table) {
            $table->string('hex_color', 20)->nullable()->after('category');
        });
    }

    public function down(): void
    {
        Schema::table('customization_options', function (Blueprint $table) {
            $table->dropColumn('hex_color');
        });
    }
};
