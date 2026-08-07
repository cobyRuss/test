<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $itemsPerPage = 6;
        $page = max(1, (int) $request->query('page', 1));
        $category = $request->query('category', 'all');
        $search = trim((string) $request->query('search', ''));

        $query = Product::query();

        if ($category !== 'all') {
            $query->where('category', $category);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $totalItems = (clone $query)->count();
        $totalPages = max(1, (int) ceil($totalItems / $itemsPerPage));

        $products = (clone $query)
            ->orderBy('id')
            ->offset(($page - 1) * $itemsPerPage)
            ->limit($itemsPerPage)
            ->get();

        $categories = Product::query()->distinct()->orderBy('category')->pluck('category');

        $contactSuccess = session('contact_success');
        $contactErrors = session('contact_errors');

        return view('home.index', compact(
            'products',
            'categories',
            'category',
            'search',
            'page',
            'totalPages',
            'totalItems',
            'contactSuccess',
            'contactErrors'
        ));
    }
}
