<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public const MAX_PRODUCT_QTY = 20;

    public function index(CartService $cart)
    {
        $items = $cart->items();
        $subtotal = $cart->subtotal();

        $customer = Auth::guard('web')->user();
        $municipality = $customer->municipality ?? 'Bangued';
        $deliveryFees = config('deliveryfees');
        $deliveryFee = $deliveryFees[$municipality] ?? 100;
        $grandTotal = $subtotal + $deliveryFee;

        return view('cart.index', compact('items', 'subtotal', 'deliveryFee', 'grandTotal', 'municipality'));
    }

    public function update(Request $request)
    {
        $cartId = $request->input('cart_id');
        $quantity = max(1, (int) $request->input('quantity'));

        if ($cartId === 'custom') {
            $custom = session('custom_arrangement');

            if (is_array($custom)) {
                $custom['quantity'] = $quantity;
                session(['custom_arrangement' => $custom]);
            }
        } else {
            if ($quantity > self::MAX_PRODUCT_QTY) {
                $quantity = self::MAX_PRODUCT_QTY;
                session()->flash('cart_error', '(!) Sorry, the maximum value is reached');
            }

            CartItem::query()
                ->where('id', $cartId)
                ->where('customer_id', Auth::guard('web')->id())
                ->update(['quantity' => $quantity]);
        }

        return redirect()->route('cart.index');
    }

    public function remove(Request $request)
    {
        $cartId = $request->input('cart_id');

        if ($cartId === 'custom') {
            session()->forget('custom_arrangement');
        } else {
            CartItem::query()
                ->where('id', $cartId)
                ->where('customer_id', Auth::guard('web')->id())
                ->delete();
        }

        return redirect()->route('cart.index');
    }

    public function add(Request $request)
    {
        $productId = (int) $request->input('product_id', 0);
        $quantity = max(1, (int) $request->input('quantity', 1));

        $product = Product::query()->with('flowerVariants')->find($productId);

        if (! $product) {
            return response()->json(['success' => false, 'message' => 'Product not found']);
        }

        if (! $product->is_available) {
            return response()->json(['success' => false, 'message' => 'This product is not available at the moment.']);
        }

        if (! Auth::guard('web')->check()) {
            return response()->json(['success' => false, 'message' => 'Please login to add items to your cart.']);
        }

        $customerId = Auth::guard('web')->id();

        $existing = CartItem::query()
            ->where('customer_id', $customerId)
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            $newQty = $existing->quantity + $quantity;

            if ($newQty > self::MAX_PRODUCT_QTY) {
                $existing->update(['quantity' => self::MAX_PRODUCT_QTY]);

                return response()->json([
                    'success' => true,
                    'message' => '(!) Sorry, the maximum value is reached',
                ]);
            }

            $existing->increment('quantity', $quantity);
        } else {
            CartItem::query()->create([
                'customer_id' => $customerId,
                'product_id' => $productId,
                'quantity' => min($quantity, self::MAX_PRODUCT_QTY),
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Added to cart']);
    }

    public function addCustom(Request $request)
    {
        if (! Auth::guard('web')->check()) {
            return response()->json([
                'success' => false,
                'login_required' => true,
                'message' => 'Please login to add items to your cart.',
            ], 401);
        }

        $name = $request->input('name', 'Custom Flower Arrangement');
        $price = (float) $request->input('price', 0);
        $description = $request->input('description', '');
        $quantity = max(1, (int) $request->input('quantity', 1));
        $totalStems = max(0, (int) $request->input('total_stems', 0));
        $items = $this->normalizeCustomItems($request->input('items'));

        $existing = session('custom_arrangement');

        if (is_array($existing)) {
            $existing['quantity'] = (int) $existing['quantity'] + $quantity;
            $existing['name'] = $name;
            $existing['price'] = $price;
            $existing['description'] = $description;
            $existing['total_stems'] = $totalStems;
            $existing['items'] = $items;
            session(['custom_arrangement' => $existing]);
        } else {
            session([
                'custom_arrangement' => [
                    'name' => $name,
                    'price' => $price,
                    'description' => $description,
                    'quantity' => $quantity,
                    'total_stems' => $totalStems,
                    'items' => $items,
                ],
            ]);
        }

        return response()->json(['success' => true]);
    }

    protected function normalizeCustomItems($raw): array
    {
        $decoded = is_string($raw) ? json_decode($raw, true) : $raw;

        if (! is_array($decoded)) {
            return [];
        }

        $items = [];

        foreach ($decoded as $row) {
            if (! is_array($row)) {
                continue;
            }

            $qty = max(1, (int) ($row['qty'] ?? 0));

            if ($qty < 1) {
                continue;
            }

            $items[] = [
                'flower' => trim((string) ($row['flower'] ?? '')),
                'color' => trim((string) ($row['color'] ?? '')),
                'size' => trim((string) ($row['size'] ?? '')),
                'qty' => $qty,
            ];
        }

        return $items;
    }

    public function count(CartService $cart)
    {
        return response()->json(['count' => $cart->count()]);
    }

    public function clear()
    {
        CartItem::query()
            ->where('customer_id', Auth::guard('web')->id())
            ->delete();

        session()->forget('custom_arrangement');

        return response()->json(['success' => true]);
    }
}
