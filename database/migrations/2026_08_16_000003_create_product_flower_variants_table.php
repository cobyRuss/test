<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('product_flower_variants')) {
            Schema::create('product_flower_variants', function (Blueprint $table) {
                $table->id();
                $table->integer('product_id');
                $table->unsignedBigInteger('variant_id');
                $table->unsignedInteger('quantity')->default(1);
                $table->unique(['product_id', 'variant_id']);
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
                $table->foreign('variant_id')->references('id')->on('customization_option_variants')->onDelete('cascade');
            });
        }

        if (Schema::hasTable('flower_product')) {
            $links = DB::table('flower_product')->get();
            $variants = DB::table('customization_option_variants')
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get()
                ->groupBy('customization_option_id');

            foreach ($links as $link) {
                $pool = $variants->get($link->flower_id, collect())->all();

                if (empty($pool)) {
                    continue;
                }

                $chosen = collect($pool)->firstWhere('is_active', 1) ?? $pool[0];

                DB::table('product_flower_variants')->insertOrIgnore([
                    'product_id' => $link->product_id,
                    'variant_id' => $chosen->id,
                    'quantity' => mt_rand(5, 30),
                ]);
            }

            foreach (DB::table('products')->get() as $product) {
                $rows = DB::table('product_flower_variants as pfv')
                    ->join('customization_option_variants as cov', 'pfv.variant_id', '=', 'cov.id')
                    ->join('customization_options as co', 'cov.customization_option_id', '=', 'co.id')
                    ->where('pfv.product_id', $product->id)
                    ->orderBy('co.sort_order')
                    ->orderBy('cov.sort_order')
                    ->orderBy('cov.id')
                    ->select('pfv.quantity', 'co.display_name as flower', 'cov.display_name as variant')
                    ->get();

                if ($rows->isEmpty()) {
                    continue;
                }

                $breakdown = $rows->map(fn ($r) => "{$r->quantity}x {$r->flower} ({$r->variant})")->implode(', ');

                DB::table('products')->where('id', $product->id)->update([
                    'description' => trim((string) $product->description)."\n\nIncludes: {$breakdown}.",
                ]);
            }

            Schema::dropIfExists('flower_product');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('product_flower_variants')) {
            if (! Schema::hasTable('flower_product')) {
                Schema::create('flower_product', function (Blueprint $table) {
                    $table->integer('product_id');
                    $table->unsignedBigInteger('flower_id');
                    $table->unique(['product_id', 'flower_id']);
                    $table->foreign('flower_id')->references('id')->on('customization_options')->onDelete('cascade');
                    $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
                });
            }

            $rows = DB::table('product_flower_variants as pfv')
                ->join('customization_option_variants as cov', 'pfv.variant_id', '=', 'cov.id')
                ->select('pfv.product_id', 'cov.customization_option_id as flower_id')
                ->distinct()
                ->get();

            foreach ($rows as $row) {
                DB::table('flower_product')->insertOrIgnore([
                    'product_id' => $row->product_id,
                    'flower_id' => $row->flower_id,
                ]);
            }

            Schema::dropIfExists('product_flower_variants');
        }
    }
};
