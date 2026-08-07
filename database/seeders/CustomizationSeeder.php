<?php

namespace Database\Seeders;

use App\Models\CustomizationOption;
use App\Models\CustomizationPreset;
use Illuminate\Database\Seeder;

class CustomizationSeeder extends Seeder
{
    public function run(): void
    {
        if (CustomizationOption::query()->count() === 0) {
            $options = [
                ['flower', 'rose', 'Roses', 120.00, 'rs.jpg', 'roses', 1],
                ['flower', 'sunflower', 'Sunflowers', 200.00, 'sf.jpg', 'sunflowers', 2],
                ['flower', 'tulip', 'Tulips', 150.00, 't1.jpg', 'tulips', 3],
                ['flower', 'lily', 'Lilies', 180.00, 'mg.jpg', 'arrangements', 4],
                ['flower', 'orchid', 'Orchids', 250.00, 'tp.jpg', 'arrangements', 5],
                ['flower', 'carnation', 'Carnations', 80.00, 'sd.jpg', 'arrangements', 6],
                ['color', 'red', 'Red', 0.00, null, null, 1],
                ['color', 'pink', 'Pink', 0.00, null, null, 2],
                ['color', 'white', 'White', 0.00, null, null, 3],
                ['color', 'yellow', 'Yellow', 0.00, null, null, 4],
                ['color', 'purple', 'Purple', 0.00, null, null, 5],
                ['color', 'mixed', 'Mixed Colors', 50.00, null, null, 6],
                ['style', 'bouquet', 'Hand-Tied Bouquet', 300.00, 'a1.jpg', null, 1],
                ['style', 'vase', 'Vase Arrangement', 500.00, 'a3.jpg', null, 2],
                ['style', 'box', 'Flower Box', 400.00, '1.jpg', null, 3],
                ['style', 'basket', 'Basket Arrangement', 450.00, 'a2.jpg', null, 4],
                ['addon', 'chocolate', 'Chocolate Box', 450.00, null, null, 1],
                ['addon', 'teddy_bear', 'Teddy Bear', 300.00, null, null, 2],
                ['addon', 'greeting_card', 'Greeting Card', 50.00, null, null, 3],
                ['addon', 'balloon', 'Balloon', 150.00, null, null, 4],
                ['addon', 'vase_upgrade', 'Premium Vase', 200.00, null, null, 5],
            ];

            foreach ($options as [$type, $name, $display, $price, $image, $category, $sort]) {
                CustomizationOption::query()->create([
                    'type' => $type,
                    'name' => $name,
                    'display_name' => $display,
                    'price' => $price,
                    'image_url' => $image,
                    'category' => $category,
                    'is_active' => true,
                    'sort_order' => $sort,
                ]);
            }
        }

        if (CustomizationPreset::query()->count() === 0) {
            $presets = [
                ['Romantic Red', 'Classic romantic arrangement with red roses', 2200.00, 'rs.jpg'],
                ['Sunny Delight', 'Bright and cheerful sunflower arrangement', 2500.00, 'sf.jpg'],
                ['Elegant White', 'Sophisticated white lily arrangement', 2800.00, 'wr.jpg'],
                ['Mixed Garden', 'Beautiful mix of seasonal flowers', 3000.00, 'mg.jpg'],
            ];

            foreach ($presets as [$name, $description, $basePrice, $image]) {
                CustomizationPreset::query()->create([
                    'name' => $name,
                    'description' => $description,
                    'base_price' => $basePrice,
                    'image_url' => $image,
                    'is_active' => true,
                ]);
            }
        }
    }
}
