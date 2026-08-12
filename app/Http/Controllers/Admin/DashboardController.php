<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\CustomizationOption;
use App\Models\CustomizationOptionVariant;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ServicePhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if ($request->isMethod('POST') && $request->has('action')) {
            $redirect = $this->handlePostActions($request);

            if ($redirect) {
                return $redirect;
            }
        }

        $activeTab = session('active_tab', 'products');
        session()->forget('active_tab');
        if ($request->has('tab')) {
            $activeTab = $request->query('tab');
        }

        $data = array_merge($this->loadProducts($request), [
            'activeTab' => $activeTab,
        ]);

        $data = array_merge($data, $this->loadCategories());
        $data = array_merge($data, $this->loadServicePhotos());
        $data = array_merge($data, $this->loadPayments());
        $data = array_merge($data, $this->loadMessages());
        $data = array_merge($data, $this->loadOrders($request));
        $data = array_merge($data, $this->loadReports($request));
        $data = array_merge($data, $this->loadCustomizationOptions());

        return view('admin.dashboard', $data);
    }

    private function handlePostActions(Request $request): ?\Illuminate\Http\RedirectResponse
    {
        $message = '';
        $activeTab = null;

        switch ($request->input('action')) {
                case 'add_product':
                    Product::query()->create([
                        'name' => $request->input('name'),
                        'description' => $request->input('description'),
                        'price' => $request->input('price'),
                        'category' => $request->input('category'),
                        'image_url' => $this->storeUploadedImage($request->file('image')),
                    ]);
                    $message = 'Product added successfully!';
                    break;

                case 'edit_product':
                    Product::query()->where('id', $request->input('id'))->update([
                        'name' => $request->input('name'),
                        'description' => $request->input('description'),
                        'price' => $request->input('price'),
                        'category' => $request->input('category'),
                        'image_url' => $request->input('image_url'),
                    ]);
                    $message = 'Product updated successfully!';
                    break;

                case 'delete_product':
                    Product::query()->where('id', $request->input('id'))->delete();
                    $message = 'Product deleted successfully!';
                    break;

                case 'add_category':
                    $catName = strtolower(trim((string) preg_replace('/\s+/', '_', (string) $request->input('cat_name'))));
                    $catDisplay = trim((string) $request->input('cat_display'));

                    if ($catName && $catDisplay) {
                        ProductCategory::query()->updateOrCreate(
                            ['slug' => $catName],
                            ['display_name' => $catDisplay]
                        );
                        $message = "Category '$catDisplay' added!";
                    }
                    session(['active_tab' => 'categories']);
                    break;

                case 'edit_category':
                    ProductCategory::query()->where('id', (int) $request->input('cat_id'))->update([
                        'display_name' => trim((string) $request->input('cat_display')),
                    ]);
                    $message = 'Category updated!';
                    session(['active_tab' => 'categories']);
                    break;

                case 'delete_category':
                    $category = ProductCategory::query()->find((int) $request->input('cat_id'));

                    if ($category) {
                        $count = Product::query()->where('category', $category->slug)->count();

                        if ($count > 0) {
                            $message = '⚠️ Cannot delete — products still use this category. Reassign them first.';
                        } else {
                            $category->delete();
                            $message = 'Category deleted!';
                        }
                    }
                    session(['active_tab' => 'categories']);
                    break;

                case 'add_service_photo':
                    ServicePhoto::query()->create([
                        'category' => $request->input('category'),
                        'image_url' => $this->storeUploadedImage($request->file('image')),
                        'caption' => trim((string) $request->input('caption')),
                    ]);
                    $message = 'Service photo added successfully!';
                    session(['active_tab' => 'services']);
                    break;

                case 'edit_service_photo':
                    $photo = ServicePhoto::query()->find((int) $request->input('id'));

                    if ($photo) {
                        $data = [
                            'category' => $request->input('category'),
                            'caption' => trim((string) $request->input('caption')),
                        ];

                        $imageUrl = $this->storeUploadedImage($request->file('image'));

                        if ($imageUrl) {
                            $data['image_url'] = $imageUrl;
                        }

                        $photo->update($data);
                        $message = 'Service photo updated successfully!';
                    }
                    session(['active_tab' => 'services']);
                    break;

                case 'delete_service_photo':
                    ServicePhoto::query()->where('id', $request->input('id'))->delete();
                    $message = 'Service photo deleted successfully!';
                    session(['active_tab' => 'services']);
                    break;

                case 'add_custom_flower':
                    CustomizationOption::query()->create([
                        'type' => 'flower',
                        'name' => strtolower(str_replace(' ', '_', (string) $request->input('name'))),
                        'display_name' => trim((string) $request->input('display_name')) ?: (string) $request->input('name'),
                        'price' => (float) $request->input('price', 0),
                        'image_url' => $this->storeUploadedImage($request->file('image')),
                        'is_active' => $request->boolean('is_active'),
                        'sort_order' => (int) $request->input('sort_order', 0),
                    ]);
                    $message = 'Flower added successfully!';
                    session(['active_tab' => 'customization']);
                    break;

                case 'edit_custom_flower':
                    $flower = CustomizationOption::query()->find((int) $request->input('id'));

                    if ($flower) {
                        $data = [
                            'name' => strtolower(str_replace(' ', '_', (string) $request->input('name'))),
                            'display_name' => trim((string) $request->input('display_name')) ?: (string) $request->input('name'),
                            'price' => (float) $request->input('price', 0),
                            'is_active' => $request->boolean('is_active'),
                            'sort_order' => (int) $request->input('sort_order', 0),
                        ];

                        $imageUrl = $this->storeUploadedImage($request->file('image'));

                        if ($imageUrl) {
                            $data['image_url'] = $imageUrl;
                        }

                        $flower->update($data);
                        $message = 'Flower updated successfully!';
                    }
                    session(['active_tab' => 'customization']);
                    break;

                case 'delete_custom_flower':
                    CustomizationOption::query()->where('id', (int) $request->input('id'))->delete();
                    $message = 'Flower deleted successfully!';
                    session(['active_tab' => 'customization']);
                    break;

                case 'add_custom_color':
                    CustomizationOption::query()->create([
                        'type' => 'color',
                        'name' => strtolower(str_replace(' ', '_', (string) $request->input('name'))),
                        'display_name' => trim((string) $request->input('display_name')) ?: (string) $request->input('name'),
                        'price' => (float) $request->input('price', 0),
                        'hex_color' => $this->normalizeHexColor($request->input('hex_color')),
                        'image_url' => $this->storeUploadedImage($request->file('image')),
                        'is_active' => $request->boolean('is_active'),
                        'sort_order' => (int) $request->input('sort_order', 0),
                    ]);
                    $message = 'Wrapper color added successfully!';
                    session(['active_tab' => 'customization']);
                    break;

                case 'edit_custom_color':
                    $color = CustomizationOption::query()->find((int) $request->input('id'));

                    if ($color) {
                        $data = [
                            'name' => strtolower(str_replace(' ', '_', (string) $request->input('name'))),
                            'display_name' => trim((string) $request->input('display_name')) ?: (string) $request->input('name'),
                            'price' => (float) $request->input('price', 0),
                            'hex_color' => $this->normalizeHexColor($request->input('hex_color')),
                            'is_active' => $request->boolean('is_active'),
                            'sort_order' => (int) $request->input('sort_order', 0),
                        ];

                        $imageUrl = $this->storeUploadedImage($request->file('image'));

                        if ($imageUrl) {
                            $data['image_url'] = $imageUrl;
                        } elseif ($request->boolean('clear_image')) {
                            $data['image_url'] = null;
                        }

                        $color->update($data);
                        $message = 'Wrapper color updated successfully!';
                    }
                    session(['active_tab' => 'customization']);
                    break;

                case 'delete_custom_color':
                    CustomizationOption::query()->where('id', (int) $request->input('id'))->delete();
                    $message = 'Wrapper color deleted successfully!';
                    session(['active_tab' => 'customization']);
                    break;

                case 'add_ribbon':
                    CustomizationOption::query()->create([
                        'type' => 'ribbon',
                        'name' => strtolower(str_replace(' ', '_', (string) $request->input('name'))),
                        'display_name' => trim((string) $request->input('display_name')) ?: (string) $request->input('name'),
                        'price' => (float) $request->input('price', 0),
                        'image_url' => $this->storeUploadedImage($request->file('image')),
                        'is_active' => $request->boolean('is_active'),
                        'sort_order' => (int) $request->input('sort_order', 0),
                    ]);
                    $message = 'Ribbon added successfully!';
                    session(['active_tab' => 'customization']);
                    break;

                case 'edit_ribbon':
                    $ribbon = CustomizationOption::query()->find((int) $request->input('id'));

                    if ($ribbon) {
                        $data = [
                            'name' => strtolower(str_replace(' ', '_', (string) $request->input('name'))),
                            'display_name' => trim((string) $request->input('display_name')) ?: (string) $request->input('name'),
                            'price' => (float) $request->input('price', 0),
                            'is_active' => $request->boolean('is_active'),
                            'sort_order' => (int) $request->input('sort_order', 0),
                        ];

                        $imageUrl = $this->storeUploadedImage($request->file('image'));

                        if ($imageUrl) {
                            $data['image_url'] = $imageUrl;
                        } elseif ($request->boolean('clear_image')) {
                            $data['image_url'] = null;
                        }

                        $ribbon->update($data);
                        $message = 'Ribbon updated successfully!';
                    }
                    session(['active_tab' => 'customization']);
                    break;

                case 'delete_ribbon':
                    CustomizationOption::query()->where('id', (int) $request->input('id'))->delete();
                    CustomizationOptionVariant::query()->where('customization_option_id', (int) $request->input('id'))->delete();
                    $message = 'Ribbon deleted successfully!';
                    session(['active_tab' => 'customization']);
                    break;

                case 'add_custom_style':
                    CustomizationOption::query()->create([
                        'type' => 'style',
                        'name' => strtolower(str_replace(' ', '_', (string) $request->input('name'))),
                        'display_name' => trim((string) $request->input('display_name')) ?: (string) $request->input('name'),
                        'price' => (float) $request->input('price', 0),
                        'image_url' => $this->storeUploadedImage($request->file('image')),
                        'is_active' => $request->boolean('is_active'),
                        'sort_order' => (int) $request->input('sort_order', 0),
                    ]);
                    $message = 'Style added successfully!';
                    session(['active_tab' => 'customization']);
                    break;

                case 'edit_custom_style':
                    $style = CustomizationOption::query()->find((int) $request->input('id'));

                    if ($style) {
                        $data = [
                            'name' => strtolower(str_replace(' ', '_', (string) $request->input('name'))),
                            'display_name' => trim((string) $request->input('display_name')) ?: (string) $request->input('name'),
                            'price' => (float) $request->input('price', 0),
                            'is_active' => $request->boolean('is_active'),
                            'sort_order' => (int) $request->input('sort_order', 0),
                        ];

                        $imageUrl = $this->storeUploadedImage($request->file('image'));

                        if ($imageUrl) {
                            $data['image_url'] = $imageUrl;
                        }

                        $style->update($data);
                        $message = 'Style updated successfully!';
                    }
                    session(['active_tab' => 'customization']);
                    break;

                case 'delete_custom_style':
                    CustomizationOption::query()->where('id', (int) $request->input('id'))->delete();
                    $message = 'Style deleted successfully!';
                    session(['active_tab' => 'customization']);
                    break;

                case 'add_filler':
                    CustomizationOption::query()->create([
                        'type' => 'filler',
                        'name' => strtolower(str_replace(' ', '_', (string) $request->input('name'))),
                        'display_name' => trim((string) $request->input('display_name')) ?: (string) $request->input('name'),
                        'price' => (float) $request->input('price', 0),
                        'image_url' => $this->storeUploadedImage($request->file('image')),
                        'is_active' => $request->boolean('is_active'),
                        'sort_order' => (int) $request->input('sort_order', 0),
                    ]);
                    $message = 'Filler added successfully!';
                    session(['active_tab' => 'customization']);
                    break;

                case 'edit_filler':
                    $filler = CustomizationOption::query()->find((int) $request->input('id'));

                    if ($filler) {
                        $data = [
                            'name' => strtolower(str_replace(' ', '_', (string) $request->input('name'))),
                            'display_name' => trim((string) $request->input('display_name')) ?: (string) $request->input('name'),
                            'price' => (float) $request->input('price', 0),
                            'is_active' => $request->boolean('is_active'),
                            'sort_order' => (int) $request->input('sort_order', 0),
                        ];

                        $imageUrl = $this->storeUploadedImage($request->file('image'));

                        if ($imageUrl) {
                            $data['image_url'] = $imageUrl;
                        }

                        $filler->update($data);
                        $message = 'Filler updated successfully!';
                    }
                    session(['active_tab' => 'customization']);
                    break;

                case 'delete_filler':
                    CustomizationOption::query()->where('id', (int) $request->input('id'))->delete();
                    $message = 'Filler deleted successfully!';
                    session(['active_tab' => 'customization']);
                    break;

                case 'add_variant':
                    CustomizationOptionVariant::query()->create([
                        'customization_option_id' => (int) $request->input('flower_id'),
                        'variant_type' => $request->input('variant_type'),
                        'display_name' => trim((string) $request->input('display_name')),
                        'price' => (float) $request->input('price', 0),
                        'hex_color' => $request->input('variant_type') === 'color' ? $this->normalizeHexColor($request->input('hex_color')) : null,
                        'image_url' => $this->storeUploadedImage($request->file('image')),
                        'is_active' => $request->boolean('is_active', true),
                        'sort_order' => (int) $request->input('sort_order', 0),
                    ]);
                    $message = 'Variant added successfully!';
                    session(['active_tab' => 'customization']);
                    break;

                case 'edit_variant':
                    $variant = CustomizationOptionVariant::query()->find((int) $request->input('id'));

                    if ($variant) {
                        $data = [
                            'customization_option_id' => (int) $request->input('flower_id', $variant->customization_option_id),
                            'variant_type' => $request->input('variant_type'),
                            'display_name' => trim((string) $request->input('display_name')),
                            'price' => (float) $request->input('price', 0),
                            'hex_color' => $request->input('variant_type') === 'color' ? $this->normalizeHexColor($request->input('hex_color')) : null,
                            'is_active' => $request->boolean('is_active', true),
                            'sort_order' => (int) $request->input('sort_order', 0),
                        ];

                        $imageUrl = $this->storeUploadedImage($request->file('image'));

                        if ($imageUrl) {
                            $data['image_url'] = $imageUrl;
                        }

                        $variant->update($data);
                        $message = 'Variant updated successfully!';
                    }
                    session(['active_tab' => 'customization']);
                    break;

                case 'delete_variant':
                    CustomizationOptionVariant::query()->where('id', (int) $request->input('id'))->delete();
                    $message = 'Variant deleted successfully!';
                    session(['active_tab' => 'customization']);
                    break;

                case 'verify_gcash':
                    DB::table('gcash_payments')->where('id', (int) $request->input('payment_id'))->update([
                        'verified' => true,
                        'verified_by' => session('admin_id') ?? Auth::guard('admin')->id(),
                        'verified_at' => now(),
                    ]);
                    DB::table('orders')->where('id', (int) $request->input('order_id'))->update([
                        'payment_status' => 'partial',
                    ]);
                    $message = 'GCash payment verified!';
                    break;

                case 'approve_order':
                    DB::table('orders')->where('id', (int) $request->input('order_id'))->update([
                        'order_status' => 'confirmed',
                    ]);
                    $message = 'Order approved!';
                    session(['active_tab' => 'orders']);
                    break;

                case 'decline_order':
                    DB::table('orders')->where('id', (int) $request->input('order_id'))->update([
                        'order_status' => 'cancelled',
                    ]);
                    $message = 'Order declined.';
                    session(['active_tab' => 'orders']);
                    break;

                case 'update_order_status':
                    $allowed = ['confirmed', 'preparing', 'ready', 'delivered', 'cancelled'];

                    if (in_array($request->input('new_status'), $allowed)) {
                        DB::table('orders')->where('id', (int) $request->input('order_id'))->update([
                            'order_status' => $request->input('new_status'),
                        ]);
                        $message = 'Order status updated!';
                    }
                    session(['active_tab' => 'orders']);
                    break;

                case 'mark_paid':
                    DB::table('orders')->where('id', (int) $request->input('order_id'))->update([
                        'payment_status' => 'completed',
                    ]);
                    $message = 'Payment marked as fully paid!';
                    session(['active_tab' => 'orders']);
                    break;
        }

        if ($activeTab) {
            session(['active_tab' => $activeTab]);
        }

        if ($message !== '') {
            session()->flash('message', $message);
        }

        return redirect()->route('admin.dashboard');
    }

    private function loadProducts(Request $request): array
    {
        $page = max(1, (int) $request->query('page', 1));
        $categoryFilter = $request->query('category_filter', '');
        $searchProduct = trim((string) $request->query('search_product', ''));
        $productsPerPage = 20;

        $query = Product::query();

        if ($categoryFilter !== '') {
            $query->where('category', $categoryFilter);
        }

        if ($searchProduct !== '') {
            $query->where(function ($q) use ($searchProduct) {
                $q->where('name', 'like', "%{$searchProduct}%")
                    ->orWhere('description', 'like', "%{$searchProduct}%");
            });
        }

        $totalProducts = (clone $query)->count();
        $totalPages = max(1, (int) ceil($totalProducts / $productsPerPage));

        $products = (clone $query)
            ->orderByDesc('id')
            ->offset(($page - 1) * $productsPerPage)
            ->limit($productsPerPage)
            ->get();

        return compact('products', 'totalProducts', 'totalPages', 'page', 'categoryFilter', 'searchProduct');
    }

    private function loadCategories(): array
    {
        $dynamicCategories = true;
        $categoriesList = [];
        $categories = [];

        try {
            $categoriesList = ProductCategory::query()->orderBy('display_name')->get();
            $categories = $categoriesList->pluck('slug')->all();
        } catch (\Exception $e) {
            $dynamicCategories = false;
            $categories = ['roses', 'sunflowers', 'tulips', 'seasonal', 'arrangements', 'wrappers'];
        }

        $categoryCounts = Product::query()
            ->select('category', DB::raw('COUNT(*) as count'))
            ->groupBy('category')
            ->pluck('count', 'category')
            ->toArray();

        return compact('dynamicCategories', 'categoriesList', 'categories', 'categoryCounts');
    }

    private function loadServicePhotos(): array
    {
        $servicePhotos = ServicePhoto::query()->orderBy('category')->orderBy('id')->get();

        $serviceCategories = ['weddings', 'events', 'corporate', 'sympathy', 'romance', 'getwell'];

        $serviceNames = [
            'weddings' => 'Weddings & Debuts',
            'events' => 'Events & Celebrations',
            'corporate' => 'Corporate & Business',
            'sympathy' => 'Sympathy & Condolences',
            'romance' => 'Love & Romance',
            'getwell' => 'Get Well & Cheer',
        ];

        return compact('servicePhotos', 'serviceCategories', 'serviceNames');
    }

    private function normalizeHexColor($value): ?string
    {
        $hex = strtolower(trim((string) $value));
        $hex = ltrim($hex, '#');

        if ($hex === '' || ! preg_match('/^[0-9a-f]{3}([0-9a-f]{3})?$/', $hex)) {
            return null;
        }

        if (strlen($hex) === 3) {
            $hex = preg_replace('/([0-9a-f])/', '$1$1', $hex);
        }

        return '#'.$hex;
    }

    private function storeUploadedImage($file): ?string
    {
        if (! $file || ! $file->isValid()) {
            return null;
        }

        $extension = $file->getClientOriginalExtension() ?: 'jpg';
        $filename = 'flower_'.time().'_'.Str::random(8).'.'.$extension;
        $file->move(public_path('images'), $filename);

        return $filename;
    }

    private function loadCustomizationOptions(): array
    {
        $customFlowers = CustomizationOption::query()
            ->where('type', 'flower')
            ->orderBy('sort_order')
            ->with('variants')
            ->get();

        $customColors = CustomizationOption::query()
            ->where('type', 'color')
            ->orderBy('sort_order')
            ->get();

        $customStyles = CustomizationOption::query()
            ->where('type', 'style')
            ->orderBy('sort_order')
            ->get();

        $customFillers = CustomizationOption::query()
            ->where('type', 'filler')
            ->orderBy('sort_order')
            ->get();

        $customRibbons = CustomizationOption::query()
            ->where('type', 'ribbon')
            ->orderBy('sort_order')
            ->with('variants')
            ->get();

        return compact('customFlowers', 'customColors', 'customStyles', 'customFillers', 'customRibbons');
    }

    private function loadPayments(): array
    {
        $pendingPayments = DB::table('gcash_payments as gp')
            ->join('orders as o', 'gp.order_id', '=', 'o.id')
            ->join('customers as c', 'o.customer_id', '=', 'c.id')
            ->where('gp.verified', false)
            ->orderByDesc('gp.created_at')
            ->select('gp.*', 'o.order_number', 'o.total_amount', 'o.down_payment', 'c.full_name', 'c.email')
            ->get();

        return compact('pendingPayments');
    }

    private function loadMessages(): array
    {
        $messages = ContactMessage::query()->orderByDesc('created_at')->get();

        return compact('messages');
    }

    private function loadOrders(Request $request): array
    {
        $orderStatusFilter = $request->query('order_status', '');
        $orderSearch = trim((string) $request->query('order_search', ''));
        $orderDateFrom = $request->query('order_date_from', '');
        $orderDateTo = $request->query('order_date_to', '');
        $ordersPage = max(1, (int) $request->query('opage', 1));
        $ordersPerPage = 15;

        $query = DB::table('orders as o')
            ->join('customers as c', 'o.customer_id', '=', 'c.id');

        if ($orderStatusFilter !== '') {
            $query->where('o.order_status', $orderStatusFilter);
        }

        if ($orderSearch !== '') {
            $query->where(function ($q) use ($orderSearch) {
                $q->where('o.order_number', 'like', "%{$orderSearch}%")
                    ->orWhere('c.full_name', 'like', "%{$orderSearch}%")
                    ->orWhere('c.email', 'like', "%{$orderSearch}%");
            });
        }

        if ($orderDateFrom !== '') {
            $query->whereDate('o.created_at', '>=', $orderDateFrom);
        }

        if ($orderDateTo !== '') {
            $query->whereDate('o.created_at', '<=', $orderDateTo);
        }

        $totalOrders = (clone $query)->count(DB::raw('DISTINCT o.id'));
        $ordersTotalPages = max(1, (int) ceil($totalOrders / $ordersPerPage));

        $orders = (clone $query)
            ->select('o.*', 'c.full_name', 'c.email', 'c.phone')
            ->selectSub(
                DB::table('order_items')->selectRaw('COUNT(*)')->whereColumn('order_id', 'o.id'),
                'item_count'
            )
            ->orderByDesc('o.created_at')
            ->offset(($ordersPage - 1) * $ordersPerPage)
            ->limit($ordersPerPage)
            ->get();

        $orderStatusCounts = DB::table('orders')
            ->select('order_status', DB::raw('COUNT(*) as cnt'))
            ->groupBy('order_status')
            ->pluck('cnt', 'order_status')
            ->toArray();

        return compact(
            'orders',
            'totalOrders',
            'ordersTotalPages',
            'ordersPage',
            'orderStatusFilter',
            'orderSearch',
            'orderDateFrom',
            'orderDateTo',
            'orderStatusCounts'
        );
    }

    private function loadReports(Request $request): array
    {
        $reportPeriod = $request->query('report_period', 'monthly');
        $reportYear = (int) $request->query('report_year', now()->year);
        $reportMonth = (int) $request->query('report_month', now()->month);
        $reportWeek = (int) $request->query('report_week', now()->isoWeek());
        $reportDay = $request->query('report_day', now()->toDateString());

        switch ($reportPeriod) {
            case 'daily':
                $dateFrom = $reportDay;
                $dateTo = $reportDay;
                $periodLabel = \Carbon\Carbon::parse($reportDay)->format('F j, Y');
                break;
            case 'weekly':
                $dto = new \DateTime();
                $dto->setISODate($reportYear, $reportWeek);
                $dateFrom = $dto->format('Y-m-d');
                $dto->modify('+6 days');
                $dateTo = $dto->format('Y-m-d');
                $periodLabel = "Week $reportWeek, $reportYear ({$dateFrom} to {$dateTo})";
                break;
            case 'annual':
                $dateFrom = "{$reportYear}-01-01";
                $dateTo = "{$reportYear}-12-31";
                $periodLabel = "Year $reportYear";
                break;
            default:
                $dateFrom = date('Y-m-01', mktime(0, 0, 0, $reportMonth, 1, $reportYear));
                $dateTo = date('Y-m-t', mktime(0, 0, 0, $reportMonth, 1, $reportYear));
                $periodLabel = date('F Y', mktime(0, 0, 0, $reportMonth, 1, $reportYear));
                break;
        }

        $reportSummary = DB::table('orders')
            ->selectRaw("COUNT(*) as total_orders,
                COALESCE(SUM(total_amount),0) as total_sales,
                COALESCE(SUM(COALESCE(delivery_fee,0)),0) as total_delivery,
                COALESCE(SUM(total_amount - COALESCE(delivery_fee,0)),0) as product_sales,
                SUM(CASE WHEN order_status='delivered' THEN 1 ELSE 0 END) as delivered,
                SUM(CASE WHEN order_status='cancelled' THEN 1 ELSE 0 END) as cancelled,
                SUM(CASE WHEN order_status='pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN payment_method='gcash' THEN 1 ELSE 0 END) as gcash_orders,
                SUM(CASE WHEN payment_method='cod' THEN 1 ELSE 0 END) as cod_orders,
                COALESCE(SUM(CASE WHEN payment_method='gcash' THEN total_amount ELSE 0 END),0) as gcash_sales,
                COALESCE(SUM(CASE WHEN payment_method='cod' THEN total_amount ELSE 0 END),0) as cod_sales")
            ->whereBetween(DB::raw('DATE(created_at)'), [$dateFrom, $dateTo])
            ->first();

        $topProducts = DB::table('order_items as oi')
            ->join('orders as o', 'oi.order_id', '=', 'o.id')
            ->whereBetween(DB::raw('DATE(o.created_at)'), [$dateFrom, $dateTo])
            ->where('o.order_status', '!=', 'cancelled')
            ->selectRaw('oi.product_name, SUM(oi.quantity) as total_qty, SUM(oi.quantity * oi.price) as total_revenue')
            ->groupBy('oi.product_name')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();

        $muniBreakdown = DB::table('orders')
            ->whereBetween(DB::raw('DATE(created_at)'), [$dateFrom, $dateTo])
            ->where('order_status', '!=', 'cancelled')
            ->selectRaw("COALESCE(municipality,'Unknown') as municipality,
                COUNT(*) as order_count,
                COALESCE(SUM(total_amount),0) as total_sales,
                COALESCE(SUM(COALESCE(delivery_fee,0)),0) as delivery_collected")
            ->groupBy('municipality')
            ->orderByDesc('total_sales')
            ->get();

        $trend = [];
        if (in_array($reportPeriod, ['monthly', 'annual'])) {
            $trendFmt = $reportPeriod === 'annual' ? '%Y-%m' : '%Y-%m-%d';

            $trend = DB::table('orders')
                ->whereBetween(DB::raw('DATE(created_at)'), [$dateFrom, $dateTo])
                ->where('order_status', '!=', 'cancelled')
                ->selectRaw("DATE_FORMAT(created_at, '{$trendFmt}') as period, COUNT(*) as orders, COALESCE(SUM(total_amount),0) as sales")
                ->groupBy('period')
                ->orderBy('period')
                ->get();
        }

        return compact(
            'reportPeriod',
            'reportYear',
            'reportMonth',
            'reportWeek',
            'reportDay',
            'periodLabel',
            'reportSummary',
            'topProducts',
            'muniBreakdown',
            'trend'
        );
    }
}
