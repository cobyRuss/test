<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('preset_items')) {
            Schema::create('preset_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('preset_id');
                $table->unsignedBigInteger('flower_id');
                $table->unsignedInteger('quantity');

                $table->foreign('preset_id')->references('id')->on('customization_presets')->onDelete('cascade');
                $table->foreign('flower_id')->references('id')->on('products')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('preset_items');
    }
};
