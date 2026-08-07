<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        if (ProductCategory::query()->count() > 0) {
            return;
        }

        $categories = [
            ['roses', 'Roses'],
            ['sunflowers', 'Sunflowers'],
            ['tulips', 'Tulips'],
            ['seasonal', 'Seasonal'],
            ['arrangements', 'Arrangements'],
            ['wrappers', 'Wrappers'],
        ];

        foreach ($categories as [$slug, $display]) {
            ProductCategory::query()->create([
                'slug' => $slug,
                'display_name' => $display,
            ]);
        }
    }
}
