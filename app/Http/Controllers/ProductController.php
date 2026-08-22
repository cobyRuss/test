<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $itemsPerPage = 12;
        $page = max(1, (int) $request->query('page', 1));
        $category = $request->query('category', 'all');
        $search = trim((string) $request->query('search', ''));

        $query = Product::query()->with(['flowerVariants.option']);

        if ($category !== 'all') {
            $query->whereHas('categories', function ($q) use ($category) {
                $q->where('slug', $category);
            });
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

        $categories = ProductCategory::query()->orderBy('display_name')->get();
        $categoryAvailability = $this->categoryAvailability();

        return view('products.index', compact('products', 'categories', 'categoryAvailability', 'category', 'search', 'page', 'totalPages', 'totalItems'));
    }

    public function show(Request $request, int $id)
    {
        $product = Product::query()->with(['categories', 'flowerVariants.option'])->findOrFail($id);

        $categoryIds = $product->categories->pluck('id');

        $related = Product::query()
            ->with('flowerVariants.option')
            ->whereHas('categories', function ($q) use ($categoryIds) {
                $q->whereIn('product_categories.id', $categoryIds);
            })
            ->where('id', '!=', $product->id)
            ->orderBy('id')
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'related'));
    }

    private function categoryAvailability(): array
    {
        $result = [];

        foreach (ProductCategory::query()->orderBy('display_name')->get() as $category) {
            $total = $category->products()->count();
            $available = $category->products()
                ->where('products.is_active', true)
                ->whereDoesntHave('flowerVariants', function ($q) {
                    $q->where('customization_option_variants.is_active', false)
                        ->orWhereHas('option', fn ($o) => $o->where('customization_options.is_active', false));
                })
                ->count();

            $result[$category->slug] = ['total' => $total, 'available' => $available];
        }

        return $result;
    }
}
