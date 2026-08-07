<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index(CartService $cart)
    {
        $customer = Auth::guard('web')->user();
        $items = $cart->items();

        if (empty($items)) {
            return redirect()->route('cart.index');
        }

        $deliveryFees = config('deliveryfees');
        $subtotal = $cart->subtotal();
        $selectedMunicipality = old('municipality', $customer->municipality ?? 'Bangued');
        $deliveryFee = $deliveryFees[$selectedMunicipality] ?? 100;
        $grandTotal = $subtotal + $deliveryFee;
        $downPayment = $grandTotal * 0.5;
        $remaining = $grandTotal - $downPayment;

        return view('checkout.index', compact(
            'customer',
            'items',
            'subtotal',
            'selectedMunicipality',
            'deliveryFee',
            'grandTotal',
            'downPayment',
            'remaining',
            'deliveryFees'
        ));
    }

    public function store(Request $request, CartService $cart)
    {
        $customer = Auth::guard('web')->user();
        $items = $cart->items();

        if (empty($items)) {
            return redirect()->route('cart.index');
        }

        $deliveryFees = config('deliveryfees');

        $data = $request->validate([
            'payment_method' => ['required', 'in:gcash,cod'],
            'municipality' => ['required', 'in:'.implode(',', array_keys($deliveryFees))],
            'street' => ['required'],
            'delivery_date' => ['required', 'date', 'after:today'],
            'special_instructions' => ['nullable'],
        ]);

        $method = $data['payment_method'];
        $subtotal = $cart->subtotal();
        $deliveryFee = $deliveryFees[$data['municipality']] ?? 100;
        $grandTotal = $subtotal + $deliveryFee;
        $downPayment = $grandTotal * 0.5;
        $remaining = $grandTotal - $downPayment;

        $orderNumber = 'ORD-'.date('Ymd').'-'.rand(1000, 9999);

        try {
            DB::beginTransaction();

            $order = Order::query()->create([
                'order_number' => $orderNumber,
                'customer_id' => $customer->id,
                'total_amount' => $grandTotal,
                'delivery_fee' => $deliveryFee,
                'down_payment' => $method === 'gcash' ? $downPayment : null,
                'remaining_balance' => $method === 'gcash' ? $remaining : null,
                'payment_method' => $method,
                'payment_status' => $method === 'gcash' ? 'pending_downpayment' : 'pending_cod',
                'order_status' => 'pending',
                'delivery_address' => $data['street'].', '.$data['municipality'].', Abra',
                'municipality' => $data['municipality'],
                'delivery_date' => $data['delivery_date'],
                'special_instructions' => $data['special_instructions'],
            ]);

            foreach ($items as $item) {
                OrderItem::query()->create([
                    'order_id' => $order->id,
                    'product_id' => $item['custom'] ? -1 : $item['product_id'],
                    'product_name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                ]);
            }

            CartItem::query()->where('customer_id', $customer->id)->delete();
            session()->forget('custom_arrangement');

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Error: '.$e->getMessage()]);
        }

        session([
            'last_order_id' => $order->id,
            'last_order_number' => $orderNumber,
        ]);

        if ($method === 'gcash') {
            return redirect()->route('orders.gcash', $order->id);
        }

        return redirect()->route('orders.show', $order->id);
    }
}
