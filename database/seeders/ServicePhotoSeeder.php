<?php

namespace Database\Seeders;

use App\Models\ServicePhoto;
use Illuminate\Database\Seeder;

class ServicePhotoSeeder extends Seeder
{
    public function run(): void
    {
        if (ServicePhoto::query()->count() > 0) {
            return;
        }

        $photos = [
            ['weddings', 'w1.jpg', 'Bridal Bouquet'],
            ['weddings', 'w2.jpg', 'Church Flowers'],
            ['weddings', 'w4.jpg', 'Reception Centerpieces'],
            ['weddings', 'w3.jpg', 'Bridesmaid Bouquets'],
            ['events', 'p1.jpg', 'U.A Pageant 2025'],
            ['events', 'p2.jpg', 'Miss Abra Pageant'],
            ['events', 'p3.jpg', 'Mr & Miss Bucay'],
            ['events', 'lr1.jpg', 'Anniversary Surprise'],
            ['corporate', 'bb1.jpg', 'BBM Building Grand Opening'],
            ['corporate', 'bbm.jpg', 'BBM Lobby Arrangement'],
            ['corporate', 'aa.jpg', 'Government Events Table Flowers'],
            ['corporate', 'aaa.jpg', 'Corporate Welcome Flowers'],
            ['sympathy', 'ccccc.jpg', 'Funeral Wreath'],
            ['sympathy', 'ccc.jpg', 'Memorial Stand'],
            ['sympathy', 'cccc.jpg', 'Condolence Basket'],
            ['sympathy', 'cc.jpg', 'Memorial Spray'],
            ['romance', 'lr1.jpg', "Valentine's Day Roses"],
            ['romance', 'lr2.jpg', 'Anniversary Arrangement'],
            ['getwell', 'sfb.jpg', 'Get Well Soon Basket'],
            ['getwell', 'gw.jpg', 'Hospital Arrangement'],
            ['getwell', 'gw1.jpg', 'Flowers with Chocolates'],
        ];

        foreach ($photos as [$category, $image, $caption]) {
            ServicePhoto::query()->create([
                'category' => $category,
                'image_url' => $image,
                'caption' => $caption,
            ]);
        }
    }
}
