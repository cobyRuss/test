<?php

namespace Database\Seeders;

use App\Models\CustomizationOption;
use App\Models\CustomizationOptionVariant;
use Illuminate\Database\Seeder;

class CustomizationVariantsSeeder extends Seeder
{
    public function run(): void
    {
        $hex = [
            'red' => '#e74c3c',
            'white' => '#f9f3f4',
            'pink' => '#e8b4bc',
            'light pink' => '#f8c8d4',
            'fuchsia pink' => '#ff4d9d',
            'yellow' => '#f1c40f',
            'orange' => '#ff991c',
            'violet' => '#9b59b6',
            'blue' => '#4169e1',
            'green' => '#008000',
        ];

        $flowers = [
            'local_roses' => ['id' => 1, 'variants' => [
                'color|Red', 'color|White', 'color|Light pink', 'color|Fuchsia pink',
            ]],
            'china_roses' => ['id' => 27, 'variants' => [
                'color|Red', 'color|White', 'color|Pink', 'color|Two-tone(White & Pink tip)',
                'color|Yellow', 'color|Orange', 'color|Violet',
            ]],
            'ecudorian_roses' => ['id' => 33, 'variants' => [
                'color|Red', 'color|White', 'color|Pink', 'color|Yellow', 'color|Orange', 'color|Blue',
            ]],
            'carnation' => ['id' => 6, 'variants' => [
                'color|White', 'color|Pink', 'color|Red', 'color|Violet', 'color|Two tone(violet, pink)',
                'color|Yellow', 'color|Orange', 'color|Green',
            ]],
            'gerbera' => ['id' => 28, 'variants' => [
                'color|White', 'color|Yellow', 'color|Red', 'color|Pink', 'color|Orange',
            ]],
            'sunflower' => ['id' => 2, 'variants' => [
                'size|Petite|120.00', 'size|Regal|150.00',
            ]],
        ];

        foreach ($flowers as $flower) {
            foreach ($flower['variants'] as $variant) {
                $parts = explode('|', $variant);
                $type = $parts[0];
                $name = $parts[1];
                $price = (float) ($parts[2] ?? 0);

                CustomizationOptionVariant::query()->updateOrCreate(
                    [
                        'customization_option_id' => $flower['id'],
                        'variant_type' => $type,
                        'display_name' => $name,
                    ],
                    [
                        'price' => $price,
                        'hex_color' => $type === 'color' ? ($hex[strtolower($name)] ?? null) : null,
                        'is_active' => true,
                    ]
                );
            }
        }

        $fillers = [
            'golden_rod' => 'Golden Rod fillers',
            'asters' => 'Asters fillers',
            'queens_ann' => 'Queens Ann fillers',
            'gypsophila' => 'Gypsophila fillers',
            'misty' => 'Misty fillers',
            'eucalyptus' => 'Eucalyptus fillers',
            'statice_caspia' => 'Statice/Caspia fillers',
        ];

        foreach ($fillers as $name => $display) {
            CustomizationOption::query()->updateOrCreate(
                ['type' => 'filler', 'name' => $name],
                [
                    'display_name' => $display,
                    'price' => 0,
                    'is_active' => true,
                    'sort_order' => array_search($name, array_keys($fillers)) + 1,
                ]
            );
        }
    }
}
