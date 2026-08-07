<?php

namespace App\Http\Controllers;

use App\Models\ServicePhoto;
use Illuminate\Http\Request;

class ServicePhotoController extends Controller
{
    public function __invoke(Request $request)
    {
        $service = $request->query('service', '');

        $serviceNames = [
            'weddings' => 'Weddings & Debuts',
            'events' => 'Events & Celebrations',
            'corporate' => 'Corporate & Business',
            'sympathy' => 'Sympathy & Condolences',
            'romance' => 'Love & Romance',
            'getwell' => 'Get Well & Cheer',
        ];

        if ($service && isset($serviceNames[$service])) {
            $photos = ServicePhoto::query()
                ->where('category', $service)
                ->orderBy('id')
                ->get();

            return response()->json([
                'title' => $serviceNames[$service].' Gallery',
                'photos' => $photos,
            ]);
        }

        return response()->json(['error' => 'Invalid service'], 400);
    }
}
