<?php

namespace App\Http\Controllers;

use App\Models\ServicePhoto;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = [
            'weddings' => [
                'title' => 'Weddings & Debuts',
                'icon' => 'fa-rings-wedding',
                'items' => [
                    'Bridal bouquets & bridesmaid bouquets',
                    'Church / ceremony flowers',
                    'Reception centerpieces & stage decor',
                    'Groom boutonnieres & corsages',
                    'Cake table & gift table flowers',
                ],
                'description' => 'From the aisle to the altar, we craft floral designs that make your special day unforgettable.',
            ],
            'events' => [
                'title' => 'Events & Celebrations',
                'icon' => 'fa-calendar-check',
                'items' => [
                    'Birthday & anniversary arrangements',
                    'Pageants, debuts & parties',
                    'Grand opening & ribbon cutting flowers',
                    'Table centerpieces for any occasion',
                    'Balloon & flower combinations',
                ],
                'description' => 'Turn every celebration into a beautiful memory with our custom event florals.',
            ],
            'corporate' => [
                'title' => 'Corporate & Business',
                'icon' => 'fa-building',
                'items' => [
                    'Office lobby arrangements',
                    'Conference & meeting table flowers',
                    'Client appreciation gifts',
                    'Grand opening flower stands',
                    'Regular office floral maintenance',
                ],
                'description' => 'Elevate your workplace with fresh, professional floral displays that leave a lasting impression.',
            ],
            'sympathy' => [
                'title' => 'Sympathy & Condolences',
                'icon' => 'fa-dove',
                'items' => [
                    'Funeral wreaths & stands',
                    'Memorial sprays',
                    'Condolence baskets',
                    'Church flowers for services',
                    'Respectful, timely delivery',
                ],
                'description' => 'Express your deepest sympathies with dignified floral tributes, handled with care and respect.',
            ],
            'romance' => [
                'title' => 'Love & Romance',
                'icon' => 'fa-heart',
                'items' => [
                    "Valentine's Day specials",
                    'Anniversary surprises',
                    'Proposal arrangements',
                    'Birthday rose deliveries',
                    'Custom love messages',
                ],
                'description' => 'Say it with flowers — romantic arrangements crafted to melt hearts.',
            ],
            'getwell' => [
                'title' => 'Get Well & Cheer',
                'icon' => 'fa-face-smile',
                'items' => [
                    'Get well soon baskets',
                    'Hospital deliveries',
                    'Congrats & new baby flowers',
                    'Cheer-up arrangements',
                    'Fruit & flower combos',
                ],
                'description' => 'Brighten someone\u2019s day with a thoughtful arrangement that shows you care.',
            ],
        ];

        $photos = ServicePhoto::query()->get()->groupBy('category');

        return view('services.index', compact('services', 'photos'));
    }
}
