<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('customization_option_variants')) {
            Schema::create('customization_option_variants', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('customization_option_id');
                $table->string('variant_type');
                $table->string('display_name');
                $table->decimal('price', 10, 2)->default(0);
                $table->string('hex_color')->nullable();
                $table->string('image_url')->nullable();
                $table->boolean('is_active')->default(true);
                $table->unsignedInteger('sort_order')->default(0);

                $table->index('customization_option_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('customization_option_variants');
    }
};
