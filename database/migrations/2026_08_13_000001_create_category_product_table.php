<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('category_product')) {
            Schema::create('category_product', function (Blueprint $table) {
                $table->integer('product_id');
                $table->integer('category_id');
                $table->unique(['product_id', 'category_id']);

                $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
                $table->foreign('category_id')->references('id')->on('product_categories')->cascadeOnDelete();
            });
        }

        if (Schema::hasColumn('products', 'category')) {
            DB::transaction(function () {
                $products = DB::table('products')
                    ->whereNotNull('category')
                    ->where('category', '!=', '')
                    ->get(['id', 'category']);

                foreach ($products as $product) {
                    $category = DB::table('product_categories')->where('slug', $product->category)->first();

                    if (! $category) {
                        $categoryId = DB::table('product_categories')->insertGetId([
                            'slug' => $product->category,
                            'display_name' => ucwords(str_replace('_', ' ', $product->category)),
                        ]);
                    } else {
                        $categoryId = $category->id;
                    }

                    DB::table('category_product')->insertOrIgnore([
                        'product_id' => $product->id,
                        'category_id' => $categoryId,
                    ]);
                }
            });

            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('category');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('products', 'category')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('category', 100)->nullable();
            });

            if (Schema::hasTable('category_product')) {
                $rows = DB::table('category_product')
                    ->join('product_categories', 'category_product.category_id', '=', 'product_categories.id')
                    ->select('category_product.product_id', 'product_categories.slug')
                    ->orderBy('category_product.id')
                    ->get();

                foreach ($rows as $row) {
                    $existing = DB::table('products')->where('id', $row->product_id)->value('category');

                    if (! $existing) {
                        DB::table('products')->where('id', $row->product_id)->update(['category' => $row->slug]);
                    }
                }

                Schema::dropIfExists('category_product');
            }
        }
    }
};
