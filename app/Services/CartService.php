<?php

namespace App\Services;

use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class CartService
{
    public function items(): array
    {
        $customer = Auth::guard('web')->user();

        if (! $customer) {
            return [];
        }

        $items = [];

        $rows = CartItem::query()
            ->where('customer_id', $customer->id)
            ->where('product_id', '!=', -1)
            ->with('product')
            ->get();

        foreach ($rows as $row) {
            if (! $row->product) {
                continue;
            }

            $items[] = [
                'cart_id' => $row->id,
                'product_id' => $row->product_id,
                'name' => $row->product->name,
                'price' => (float) $row->product->price,
                'quantity' => (int) $row->quantity,
                'image_url' => $row->product->image_url,
                'custom' => false,
            ];
        }

        $custom = session('custom_arrangement');

        if (is_array($custom) && (int) ($custom['quantity'] ?? 0) > 0) {
            $items[] = [
                'cart_id' => 'custom',
                'product_id' => -1,
                'name' => $custom['name'] ?? 'Custom Flower Arrangement',
                'price' => (float) ($custom['price'] ?? 0),
                'quantity' => (int) ($custom['quantity'] ?? 1),
                'image_url' => null,
                'custom' => true,
                'description' => $custom['description'] ?? '',
            ];
        }

        return $items;
    }

    public function subtotal(): float
    {
        $sum = 0;

        foreach ($this->items() as $item) {
            $sum += $item['price'] * $item['quantity'];
        }

        return $sum;
    }

    public function count(): int
    {
        $customer = Auth::guard('web')->user();

        if (! $customer) {
            return 0;
        }

        $count = (int) CartItem::query()
            ->where('customer_id', $customer->id)
            ->where('product_id', '!=', -1)
            ->sum('quantity');

        $custom = session('custom_arrangement');

        if (is_array($custom)) {
            $count += (int) ($custom['quantity'] ?? 0);
        }

        return $count;
    }
}
