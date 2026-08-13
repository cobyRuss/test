<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('customization_options', 'stock_quantity')) {
            Schema::table('customization_options', function (Blueprint $table) {
                $table->unsignedInteger('stock_quantity')->default(100)->after('sort_order');
            });
        }

        if (! Schema::hasColumn('products', 'is_active')) {
            Schema::table('products', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('image_url');
            });
        }

        if (! Schema::hasTable('flower_product')) {
            Schema::create('flower_product', function (Blueprint $table) {
                $table->integer('product_id');
                $table->integer('flower_id');
                $table->unique(['product_id', 'flower_id']);

                $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
                $table->foreign('flower_id')->references('id')->on('customization_options')->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('flower_product');

        if (Schema::hasColumn('products', 'is_active')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('is_active');
            });
        }

        if (Schema::hasColumn('customization_options', 'stock_quantity')) {
            Schema::table('customization_options', function (Blueprint $table) {
                $table->dropColumn('stock_quantity');
            });
        }
    }
};
