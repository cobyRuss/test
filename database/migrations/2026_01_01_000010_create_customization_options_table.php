<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('customization_options')) {
            Schema::create('customization_options', function (Blueprint $table) {
                $table->id();
                $table->string('type');
                $table->string('name');
                $table->string('display_name');
                $table->decimal('price', 10, 2)->default(0);
                $table->string('image_url')->nullable();
                $table->string('category')->nullable();
                $table->boolean('is_active')->default(true);
                $table->unsignedInteger('sort_order')->default(0);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('customization_options');
    }
};
