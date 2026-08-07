<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        if (Product::query()->count() > 0) {
            return;
        }

        $products = [
            ['Red Romance Bouquet', 'A classic arrangement of deep red roses, symbolizing love and passion.', 2300.00, 'roses', 'rs.jpg'],
            ['Sunflower Symphony', 'A vibrant arrangement of cheerful sunflowers to brighten any room with sunny energy.', 10500.00, 'sunflowers', 'sf.jpg'],
            ['Pastel Tulip Elegance', 'Soft pastel tulips arranged to create an elegant and sophisticated spring display.', 1450.00, 'tulips', 't1.jpg'],
            ['Mixed Garden Bouquet', 'A beautiful assortment of seasonal flowers for a natural garden feel.', 1550.00, 'seasonal', 'mg.jpg'],
            ['Tropical Paradise', 'Exotic flowers that bring a vibrant, tropical feel to any space.', 2550.00, 'arrangements', 'tp.jpg'],
            ['Sunshine Daisies', 'Cheerful daisies that spread happiness and brighten your day.', 1800.00, 'arrangements', 'sd.jpg'],
            ['Pink Perfection', 'Delicate pink roses that express admiration and gratitude.', 1800.00, 'roses', 'pr.jpg'],
            ['White Innocence', 'Pure white roses symbolizing purity, innocence, and new beginnings.', 1550.00, 'roses', 'wr.jpg'],
            ['Golden Friendship', 'Bright yellow roses representing friendship, joy, and caring.', 3400.00, 'roses', 'yr.jpg'],
            ['Lavender Enchantment', 'Mystical lavender roses that convey enchantment and love at first sight.', 5200.00, 'roses', 'lr.jpg'],
            ['Red Gratitude', 'Red roses expressing appreciation and thankfulness.', 4900.00, 'roses', 'ra.jpg'],
            ['Golden Sunshine', 'A vibrant mix of sunflowers that brings warmth and happiness to any space.', 1400.00, 'sunflowers', 's2.jpg'],
            ['Summer Field', 'An abundant arrangement that captures the essence of a sunny summer field.', 900.00, 'sunflowers', 's3.jpg'],
            ['Mini Delight', 'Charming mini sunflowers perfect for adding a cheerful touch to any room.', 1300.00, 'sunflowers', 's4.jpg'],
            ['Sunny Mix', 'A delightful combination of sunflowers and daisies for a bright, cheerful display.', 2300.00, 'sunflowers', 's5.jpg'],
            ['Autumn Picnic', 'Rich, warm sunflowers in a box.', 2500.00, 'sunflowers', 's6.jpg'],
            ['Gradient Delight', 'A vibrant mix of colorful white and pink that brings joy and cheer to any space.', 1600.00, 'tulips', 'ct.jpg'],
            ['Soft Passion', 'Light pink tulips that symbolize perfect love and passion.', 2750.00, 'tulips', 'pt.jpg'],
            ['Blue Royalty', 'Regal Blue tulips that represent royalty and elegance.', 4200.00, 'tulips', 'bt.jpg'],
            ['Yellow Sunshine', 'Cheerful mixed tulips that bring sunshine and happiness wherever they go.', 3000.00, 'tulips', 'mt.avif'],
            ['White Purity', 'Pure white tulips symbolizing forgiveness, respect, and purity.', 2500.00, 'tulips', 'wt.jpg'],
            ['Spring Blossoms', 'Fresh spring flowers that capture the renewal and beauty of the season.', 1100.00, 'seasonal', 'ss1.jpg'],
            ['Summer Blooms', 'Vibrant summer flowers that bring warmth and energy to any space.', 1150.00, 'seasonal', 'ss2.jpg'],
            ['Autumn Harvest', 'Rich, warm flowers that capture the cozy essence of autumn.', 1350.00, 'seasonal', 'ss3.jpg'],
            ['Winter Wonder', 'Elegant winter flowers that bring beauty to the coldest season.', 1200.00, 'seasonal', 'ss4.jpg'],
            ['Year-Round Beauty', 'A timeless arrangement of flowers that brings joy in every season.', 2200.00, 'seasonal', 'ss5.jpg'],
            ['Classic Elegance', 'A sophisticated arrangement of exotic tropical flowers for timeless beauty.', 2600.00, 'arrangements', 'a1.jpg'],
            ['Contemporary Charm', 'A modern floral arrangement perfect as a stunning centerpiece.', 1300.00, 'arrangements', 'a2.jpg'],
            ['Lush Garden', 'An abundant arrangement that brings the beauty of a garden indoors.', 1500.00, 'arrangements', 'a3.jpg'],
            ['Artistic Display', 'A creatively designed floral arrangement that showcases artistic flair.', 2800.00, 'arrangements', 'a4.jpg'],
            ['Mixed Wrapper', 'Beautiful mixed wrapper for flowers.', 25.00, 'wrappers', 'mixed.webp'],
            ['Pink & Blue', 'Lovely pink and blue wrapper.', 10.00, 'wrappers', 'pinkblue.webp'],
            ['Lilac', 'Elegant lilac colored wrapper.', 15.00, 'wrappers', 'lilac.webp'],
            ['Beautiful Wrapper 4', 'Light brown natural wrapper.', 18.00, 'wrappers', 'lightbrown.webp'],
            ['Beautiful Wrapper 5', 'Dark red sophisticated wrapper.', 14.00, 'wrappers', 'darkred.webp'],
            ['Beautiful Wrapper 6', 'Green and blue artistic wrapper.', 20.00, 'wrappers', 'greenblue.webp'],
        ];

        foreach ($products as [$name, $description, $price, $category, $image]) {
            Product::query()->create([
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'category' => $category,
                'image_url' => $image,
            ]);
        }
    }
}
