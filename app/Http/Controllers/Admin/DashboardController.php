<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Customer;
use App\Models\CustomizationOption;
use App\Models\CustomizationOptionVariant;
use App\Models\GcashPayment;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Review;
use App\Models\ReviewPhoto;
use App\Models\ServicePhoto;
use App\Services\NotificationService;
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
        $data = array_merge($data, $this->loadMessages($request));
        $data = array_merge($data, $this->loadOrders($request));
        $data = array_merge($data, $this->loadReports($request));
        $data = array_merge($data, $this->loadCustomizationOptions($request));
        $data = array_merge($data, $this->loadNotifications($request));
        $data = array_merge($data, $this->loadReviews($request));

        return view('admin.dashboard', $data);
    }

    private function handlePostActions(Request $request): ?\Illuminate\Http\RedirectResponse
    {
        $message = '';
        $activeTab = null;

        switch ($request->input('action')) {
                case 'add_product':
                    $request->validate([
                        'image' => ['required', 'image', 'max:5120'],
                    ]);
                    $product = Product::query()->create([
                        'name' => $request->input('name'),
                        'description' => $request->input('description') ?? '',
                        'price' => $request->input('price'),
                        'image_url' => $this->storeUploadedImage($request->file('image')),
                        'is_active' => $request->boolean('is_active'),
                    ]);
                    $product->categories()->sync(array_map('intval', (array) $request->input('categories', [])));
                    $this->syncProductVariants($product, $request);

                    if (! $request->filled('description')) {
                        $product->update(['description' => $this->flowerBreakdown($product)]);
                    }
                    $message = 'Product added successfully!';
                    break;

                case 'edit_product':
                    $product = Product::query()->find((int) $request->input('id'));

                    if ($product) {
                        $data = [
                            'name' => $request->input('name'),
                            'description' => $request->input('description'),
                            'price' => $request->input('price'),
                            'is_active' => $request->boolean('is_active'),
                        ];

                        if ($request->filled('image_url')) {
                            $data['image_url'] = $request->input('image_url');
                        }

                        $imageUrl = $this->storeUploadedImage($request->file('image'));

                        if ($imageUrl) {
                            $data['image_url'] = $imageUrl;
                        }

                        $product->update($data);
                        $product->categories()->sync(array_map('intval', (array) $request->input('categories', [])));
                        $this->syncProductVariants($product, $request);
                        $message = 'Product updated successfully!';
                    }
                    break;

                case 'delete_product':
                    Product::query()->where('id', $request->input('id'))->delete();
                    $message = 'Product deleted successfully!';
                    break;

                case 'add_category':
                    $catDisplay = trim((string) $request->input('cat_display'));

                    if ($catDisplay) {
                        $catName = $this->slugifyFlowerName($catDisplay);
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
                        $count = DB::table('category_product')->where('category_id', $category->id)->count();

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
                    $displayName = trim((string) $request->input('display_name'));
                    CustomizationOption::query()->create([
                        'type' => 'flower',
                        'name' => $this->slugifyFlowerName($displayName),
                        'display_name' => $displayName,
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
                            'name' => $this->slugifyFlowerName((string) $request->input('name')) ?: $this->slugifyFlowerName((string) $request->input('display_name')),
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
                        'name' => $this->slugifyFlowerName((string) $request->input('display_name')),
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
                            'name' => $this->slugifyFlowerName((string) $request->input('name')) ?: $this->slugifyFlowerName((string) $request->input('display_name')),
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
                        'name' => $this->slugifyFlowerName((string) $request->input('display_name')),
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
                            'name' => $this->slugifyFlowerName((string) $request->input('name')) ?: $this->slugifyFlowerName((string) $request->input('display_name')),
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
                        'name' => $this->slugifyFlowerName((string) $request->input('display_name')),
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
                            'name' => $this->slugifyFlowerName((string) $request->input('name')) ?: $this->slugifyFlowerName((string) $request->input('display_name')),
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
                        'name' => $this->slugifyFlowerName((string) $request->input('display_name')),
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
                            'name' => $this->slugifyFlowerName((string) $request->input('name')) ?: $this->slugifyFlowerName((string) $request->input('display_name')),
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
                        'payment_status' => 'completed',
                    ]);
                    $order = Order::query()->find((int) $request->input('order_id'));

                    if ($order) {
                        NotificationService::sendToCustomer(
                            $order->customer_id,
                            'payment_confirmed',
                            'Payment verified',
                            'Your GCash payment for order '.$order->order_number.' has been confirmed. Thank you!',
                            route('orders.show', $order->id)
                        );
                    }
                    $message = 'GCash payment verified — order marked as fully paid!';
                    break;

                case 'approve_order':
                    DB::table('orders')->where('id', (int) $request->input('order_id'))->update([
                        'order_status' => 'confirmed',
                    ]);
                    $order = Order::query()->find((int) $request->input('order_id'));

                    if ($order) {
                        NotificationService::sendToCustomer(
                            $order->customer_id,
                            'order_status',
                            'Order confirmed',
                            'Your order '.$order->order_number.' has been confirmed. We\'re getting your flowers ready!',
                            route('orders.show', $order->id)
                        );
                    }
                    $message = 'Order approved!';
                    session(['active_tab' => 'orders']);
                    break;

                case 'decline_order':
                    DB::table('orders')->where('id', (int) $request->input('order_id'))->update([
                        'order_status' => 'cancelled',
                    ]);
                    $order = Order::query()->find((int) $request->input('order_id'));

                    if ($order) {
                        NotificationService::sendToCustomer(
                            $order->customer_id,
                            'order_status',
                            'Order declined',
                            'We\'re sorry, your order '.$order->order_number.' was not approved. Please contact us for details.',
                            route('orders.show', $order->id)
                        );
                    }
                    $message = 'Order declined.';
                    session(['active_tab' => 'orders']);
                    break;

                case 'update_order_status':
                    $allowed = ['confirmed', 'preparing', 'ready', 'delivered', 'cancelled'];

                    if (in_array($request->input('new_status'), $allowed)) {
                        DB::table('orders')->where('id', (int) $request->input('order_id'))->update([
                            'order_status' => $request->input('new_status'),
                        ]);
                        $order = Order::query()->find((int) $request->input('order_id'));

                        if ($order) {
                            $statusMessages = [
                                'confirmed' => ['Order confirmed', 'Your order '.$order->order_number.' has been confirmed. We\'re getting your flowers ready!'],
                                'preparing' => ['Order preparing', 'Your order '.$order->order_number.' is being prepared — your flowers are coming together!'],
                                'ready' => ['Ready for delivery', 'Your order '.$order->order_number.' is ready for delivery!'],
                                'delivered' => ['Order delivered', 'Your order '.$order->order_number.' has been delivered. Thank you for shopping with HappyStem!'],
                                'cancelled' => ['Order cancelled', 'Your order '.$order->order_number.' has been cancelled.'],
                            ];
                            [$title, $body] = $statusMessages[$request->input('new_status')];
                            NotificationService::sendToCustomer(
                                $order->customer_id,
                                'order_status',
                                $title,
                                $body,
                                route('orders.show', $order->id)
                            );
                        }
                        $message = 'Order status updated!';
                    }
                    session(['active_tab' => 'orders']);
                    break;

                case 'mark_paid':
                    DB::table('orders')->where('id', (int) $request->input('order_id'))->update([
                        'payment_status' => 'completed',
                    ]);
                    $order = Order::query()->find((int) $request->input('order_id'));

                    if ($order) {
                        NotificationService::sendToCustomer(
                            $order->customer_id,
                            'payment_confirmed',
                            'Payment confirmed',
                            'Your payment for order '.$order->order_number.' has been marked as paid.',
                            route('orders.show', $order->id)
                        );
                    }
                    $message = 'Payment marked as fully paid!';
                    session(['active_tab' => 'orders']);
                    break;

                case 'reply_message':
                    $contactMessage = ContactMessage::query()->find((int) $request->input('message_id'));
                    $replyText = trim((string) $request->input('admin_reply'));

                    if ($contactMessage && $replyText !== '') {
                        $contactMessage->update([
                            'admin_reply' => $replyText,
                            'replied_at' => now(),
                        ]);
                        $customer = $contactMessage->customer_id
                            ? Customer::query()->find($contactMessage->customer_id)
                            : Customer::query()->where('email', $contactMessage->email)->first();

                        if ($customer) {
                            NotificationService::sendToCustomer(
                                $customer->id,
                                'admin_reply',
                                'New reply from HappyStem',
                                Str::limit($replyText, 90),
                                route('account.messages')
                            );
                        }
                        $message = 'Reply sent!';
                    } elseif (! $contactMessage) {
                        $message = 'Message not found.';
                    } else {
                        $message = 'Reply cannot be empty.';
                    }
                    session(['active_tab' => 'messages']);
                    break;

                case 'hide_review':
                    $review = Review::query()->find((int) $request->input('review_id'));
                    if ($review) {
                        $review->update(['is_visible' => false]);
                        NotificationService::sendToCustomer(
                            $review->customer_id,
                            'review_hidden',
                            'Your review has been hidden',
                            'Your review for "'.$review->product->name.'" has been hidden by the admin.',
                            ''
                        );
                        $message = 'Review hidden.';
                    } else {
                        $message = 'Review not found.';
                    }
                    session(['active_tab' => 'reviews']);
                    break;

                case 'show_review':
                    $review = Review::query()->find((int) $request->input('review_id'));
                    if ($review) {
                        $review->update(['is_visible' => true]);
                        $message = 'Review is now visible.';
                    } else {
                        $message = 'Review not found.';
                    }
                    session(['active_tab' => 'reviews']);
                    break;

                case 'delete_review':
                    $review = Review::query()->find((int) $request->input('review_id'));
                    if ($review) {
                        foreach ($review->photos as $photo) {
                            $path = public_path('images/'.$photo->image_url);
                            if (file_exists($path)) {
                                unlink($path);
                            }
                        }
                        $review->delete();
                        $message = 'Review deleted permanently.';
                    } else {
                        $message = 'Review not found.';
                    }
                    session(['active_tab' => 'reviews']);
                    break;
        }

        if ($activeTab) {
            session(['active_tab' => $activeTab]);
        }

        if ($message !== '') {
            session()->flash('message', $message);
        }

        $redirectUrl = route('admin.dashboard');
        $refererQuery = (string) parse_url((string) $request->headers->get('referer', ''), PHP_URL_QUERY);

        if ($refererQuery !== '') {
            $redirectUrl .= '?' . $refererQuery;
        }

        return redirect($redirectUrl);
    }

    private function loadProducts(Request $request): array
    {
        $page = max(1, (int) $request->query('page', 1));
        $categoryFilter = $request->query('category_filter', '');
        $searchProduct = trim((string) $request->query('search_product', ''));
        $productsPerPage = 20;

        $query = Product::query();

        if ($categoryFilter !== '') {
            $query->whereHas('categories', function ($q) use ($categoryFilter) {
                $q->where('slug', $categoryFilter);
            });
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
            ->with(['categories', 'flowerVariants'])
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

        $categoryCounts = DB::table('category_product as cp')
            ->join('product_categories as pc', 'cp.category_id', '=', 'pc.id')
            ->select('pc.slug', DB::raw('COUNT(*) as count'))
            ->groupBy('pc.slug')
            ->pluck('count', 'slug')
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

    private function slugifyFlowerName(string $value): string
    {
        $slug = strtolower(trim((string) preg_replace('/[^a-zA-Z0-9]+/', '_', $value), '_'));

        return $slug === '' ? 'flower_'.time() : $slug;
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

    private function syncProductVariants(Product $product, Request $request): void
    {
        $flowerVariantIds = CustomizationOptionVariant::query()
            ->whereHas('option', fn ($q) => $q->where('type', 'flower'))
            ->pluck('id')
            ->flip()
            ->all();

        $checked = array_filter((array) $request->input('variants', []));
        $quantities = (array) $request->input('variant_qty', []);
        $sync = [];

        foreach ($checked as $variantId => $on) {
            $variantId = (int) $variantId;

            if (! $on || ! isset($flowerVariantIds[$variantId])) {
                continue;
            }

            $raw = $quantities[$variantId] ?? '';
            $quantity = $raw === '' || $raw === null ? mt_rand(5, 30) : max(1, (int) $raw);

            $sync[$variantId] = ['quantity' => $quantity];
        }

        $product->flowerVariants()->sync($sync);
    }

    private function flowerBreakdown(Product $product): string
    {
        $parts = $product->flowerVariants->map(function ($variant) {
            $flower = $variant->option->display_name ?? 'Flower';

            return $variant->pivot->quantity.'x '.$flower.' ('.$variant->display_name.')';
        });

        return $parts->isEmpty() ? '' : 'Includes: '.$parts->implode(', ').'.';
    }

    private function loadCustomizationOptions(Request $request): array
    {
        $perPage = 20;

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

        $flowersPage = max(1, (int) $request->query('cfpage', 1));
        $flowersTotal = $customFlowers->count();
        $flowersTotalPages = max(1, (int) ceil($flowersTotal / $perPage));
        $flowersPaged = $customFlowers->slice(($flowersPage - 1) * $perPage, $perPage)->values();

        $fillersPage = max(1, (int) $request->query('fpage', 1));
        $fillersTotal = $customFillers->count();
        $fillersTotalPages = max(1, (int) ceil($fillersTotal / $perPage));
        $fillersPaged = $customFillers->slice(($fillersPage - 1) * $perPage, $perPage)->values();

        $colorsPage = max(1, (int) $request->query('cpage', 1));
        $colorsTotal = $customColors->count();
        $colorsTotalPages = max(1, (int) ceil($colorsTotal / $perPage));
        $colorsPaged = $customColors->slice(($colorsPage - 1) * $perPage, $perPage)->values();

        $ribbonsPage = max(1, (int) $request->query('rpage', 1));
        $ribbonsTotal = $customRibbons->count();
        $ribbonsTotalPages = max(1, (int) ceil($ribbonsTotal / $perPage));
        $ribbonsPaged = $customRibbons->slice(($ribbonsPage - 1) * $perPage, $perPage)->values();

        $stylesPage = max(1, (int) $request->query('spage', 1));
        $stylesTotal = $customStyles->count();
        $stylesTotalPages = max(1, (int) ceil($stylesTotal / $perPage));
        $stylesPaged = $customStyles->slice(($stylesPage - 1) * $perPage, $perPage)->values();

        $flowerVariants = CustomizationOptionVariant::query()
            ->whereHas('option', fn ($q) => $q->where('type', 'flower'))
            ->with('option')
            ->orderBy('sort_order')
            ->orderBy('id');

        $flowerVariantsPage = max(1, (int) $request->query('fvpage', 1));
        $flowerVariantsTotal = (clone $flowerVariants)->count();
        $flowerVariantsTotalPages = max(1, (int) ceil($flowerVariantsTotal / $perPage));
        $flowerVariantsPaged = (clone $flowerVariants)
            ->offset(($flowerVariantsPage - 1) * $perPage)
            ->limit($perPage)
            ->get();

        $ribbonVariants = CustomizationOptionVariant::query()
            ->whereHas('option', fn ($q) => $q->where('type', 'ribbon'))
            ->with('option')
            ->orderBy('sort_order')
            ->orderBy('id');

        $ribbonVariantsPage = max(1, (int) $request->query('rvpage', 1));
        $ribbonVariantsTotal = (clone $ribbonVariants)->count();
        $ribbonVariantsTotalPages = max(1, (int) ceil($ribbonVariantsTotal / $perPage));
        $ribbonVariantsPaged = (clone $ribbonVariants)
            ->offset(($ribbonVariantsPage - 1) * $perPage)
            ->limit($perPage)
            ->get();

        return compact(
            'customFlowers', 'customColors', 'customStyles', 'customFillers', 'customRibbons',
            'flowersPaged', 'flowersTotal', 'flowersTotalPages', 'flowersPage',
            'fillersPaged', 'fillersTotal', 'fillersTotalPages', 'fillersPage',
            'colorsPaged', 'colorsTotal', 'colorsTotalPages', 'colorsPage',
            'ribbonsPaged', 'ribbonsTotal', 'ribbonsTotalPages', 'ribbonsPage',
            'stylesPaged', 'stylesTotal', 'stylesTotalPages', 'stylesPage',
            'flowerVariantsPaged', 'flowerVariantsTotal', 'flowerVariantsTotalPages', 'flowerVariantsPage',
            'ribbonVariantsPaged', 'ribbonVariantsTotal', 'ribbonVariantsTotalPages', 'ribbonVariantsPage'
        );
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

    private function loadNotifications(Request $request): array
    {
        $adminId = Auth::guard('admin')->id();
        $notificationsPage = max(1, (int) $request->query('npage', 1));
        $notificationsPerPage = 20;

        $query = Notification::query()->forAdmin($adminId);

        $totalNotifications = (clone $query)->count();
        $notificationsTotalPages = max(1, (int) ceil($totalNotifications / $notificationsPerPage));

        $notifications = (clone $query)
            ->orderByDesc('created_at')
            ->offset(($notificationsPage - 1) * $notificationsPerPage)
            ->limit($notificationsPerPage)
            ->get();

        $defaultReply = ContactMessage::DEFAULT_REPLY;

        return compact('notifications', 'totalNotifications', 'notificationsTotalPages', 'notificationsPage', 'defaultReply');
    }

    private function loadReviews(Request $request): array
    {
        $reviewsPage = max(1, (int) $request->query('rpage', 1));
        $reviewsPerPage = 20;
        $reviewFilter = $request->query('review_filter', 'all');

        $query = Review::query()->with('customer', 'product', 'photos');

        if ($reviewFilter === 'visible') {
            $query->where('is_visible', true);
        } elseif ($reviewFilter === 'hidden') {
            $query->where('is_visible', false);
        }

        $totalReviews = (clone $query)->count();
        $reviewsTotalPages = max(1, (int) ceil($totalReviews / $reviewsPerPage));

        $reviews = (clone $query)
            ->orderByDesc('created_at')
            ->offset(($reviewsPage - 1) * $reviewsPerPage)
            ->limit($reviewsPerPage)
            ->get();

        return compact('reviews', 'totalReviews', 'reviewsTotalPages', 'reviewsPage', 'reviewFilter');
    }

    private function loadMessages(Request $request): array
    {
        $messageSearch = trim((string) $request->query('message_search', ''));
        $messagesPage = max(1, (int) $request->query('mpage', 1));
        $messagesPerPage = 20;

        $query = ContactMessage::query();

        if ($messageSearch !== '') {
            $query->where(function ($q) use ($messageSearch) {
                $q->where('name', 'like', "%{$messageSearch}%")
                    ->orWhere('email', 'like', "%{$messageSearch}%")
                    ->orWhere('message', 'like', "%{$messageSearch}%");
            });
        }

        $totalMessages = (clone $query)->count();
        $messagesTotalPages = max(1, (int) ceil($totalMessages / $messagesPerPage));

        $messages = (clone $query)
            ->orderByDesc('created_at')
            ->offset(($messagesPage - 1) * $messagesPerPage)
            ->limit($messagesPerPage)
            ->get();

        return compact('messages', 'totalMessages', 'messagesTotalPages', 'messagesPage', 'messageSearch');
    }

    private function loadOrders(Request $request): array
    {
        $orderStatusFilter = $request->query('order_status', '');
        $orderSearch = trim((string) $request->query('order_search', ''));
        $orderDateFrom = $request->query('order_date_from', '');
        $orderDateTo = $request->query('order_date_to', '');
        $ordersPage = max(1, (int) $request->query('opage', 1));
        $ordersPerPage = 20;

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
            ->selectSub(
                DB::table('gcash_payments')
                    ->select('screenshot_path')
                    ->whereColumn('order_id', 'o.id')
                    ->orderByDesc('id')
                    ->limit(1),
                'gcash_screenshot'
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

        $trend = collect();
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

    public function orderDetails(int $id)
    {
        $order = Order::query()->with('customer')->findOrFail($id);

        $items = OrderItem::query()->where('order_id', $order->id)->get();
        $gcashPayment = GcashPayment::query()->where('order_id', $order->id)->orderByDesc('id')->first();

        $paymentLabels = [
            'pending_downpayment' => 'Unpaid',
            'partial' => 'Payment Submitted',
            'completed' => 'Paid',
            'pending_cod' => 'COD',
        ];

        return view('admin.order_details', compact('order', 'items', 'gcashPayment', 'paymentLabels'));
    }
}
