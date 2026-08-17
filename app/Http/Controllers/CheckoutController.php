<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use App\Services\NotificationService;
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

        return view('checkout.index', compact(
            'customer',
            'items',
            'subtotal',
            'selectedMunicipality',
            'deliveryFee',
            'grandTotal',
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
        $phoneRule = ['required', 'regex:/^9\d{9}$/'];

        $data = $request->validate([
            'sender_first_name' => ['required'],
            'sender_last_name' => ['required'],
            'sender_phone' => $phoneRule,
            'recipient_mode' => ['required', 'in:me,someone_else'],
            'recipient_first_name' => ['required_if:recipient_mode,someone_else'],
            'recipient_last_name' => ['required_if:recipient_mode,someone_else'],
            'recipient_phone' => $request->input('recipient_mode') === 'someone_else' ? $phoneRule : ['nullable'],
            'payment_method' => ['required', 'in:gcash'],
            'municipality' => ['required', 'in:'.implode(',', array_keys($deliveryFees))],
            'barangay' => ['required'],
            'street' => ['required'],
            'delivery_date' => ['required', 'date', 'after:today'],
            'message_for_recipient' => ['nullable', 'max:400'],
            'special_instructions' => ['nullable'],
            'sender_anonymous' => ['nullable'],
        ], [
            'sender_phone.regex' => 'Enter a valid 10-digit mobile number (e.g. 9171234567).',
            'recipient_phone.regex' => 'Enter a valid 10-digit mobile number (e.g. 9171234567).',
            'recipient_phone.required' => 'Recipient phone number is required.',
            'recipient_first_name.required_if' => 'Recipient first name is required.',
            'recipient_last_name.required_if' => 'Recipient last name is required.',
            'message_for_recipient.max' => 'Message for recipient must be 400 characters or fewer.',
        ]);

        $method = $data['payment_method'];
        $subtotal = $cart->subtotal();
        $deliveryFee = $deliveryFees[$data['municipality']] ?? 100;
        $grandTotal = $subtotal + $deliveryFee;
        $downPayment = $grandTotal;
        $remaining = 0;

        $senderPhone = $data['sender_phone'];

        if ($data['recipient_mode'] === 'someone_else') {
            $recipientName = trim($data['recipient_first_name'].' '.$data['recipient_last_name']);
            $recipientPhone = $data['recipient_phone'];
        } else {
            $recipientName = trim($data['sender_first_name'].' '.$data['sender_last_name']);
            $recipientPhone = $senderPhone;
        }

        $deliveryAddress = trim($data['street'].', '.$data['barangay'].', '.$data['municipality'].', Abra');
        $orderNumber = 'ORD-'.date('Ymd').'-'.rand(1000, 9999);

        try {
            DB::beginTransaction();

            $order = Order::query()->create([
                'order_number' => $orderNumber,
                'customer_id' => $customer->id,
                'sender_phone' => $senderPhone,
                'recipient_name' => $recipientName,
                'recipient_phone' => $recipientPhone,
                'total_amount' => $grandTotal,
                'delivery_fee' => $deliveryFee,
                'down_payment' => $downPayment,
                'remaining_balance' => $remaining,
                'payment_method' => $method,
                'payment_status' => 'pending_downpayment',
                'order_status' => 'pending',
                'delivery_address' => $deliveryAddress,
                'municipality' => $data['municipality'],
                'recipient_barangay' => $data['barangay'],
                'recipient_street' => $data['street'],
                'delivery_date' => $data['delivery_date'],
                'special_instructions' => $data['special_instructions'] ?? null,
                'message_for_recipient' => $data['message_for_recipient'] ?? null,
                'sender_anonymous' => isset($data['sender_anonymous']) ? 1 : 0,
            ]);

            foreach ($items as $item) {
                OrderItem::query()->create([
                    'order_id' => $order->id,
                    'product_id' => $item['custom'] ? -1 : $item['product_id'],
                    'product_name' => $item['name'],
                    'description' => $item['custom'] ? ($item['description'] ?? '') : null,
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

        NotificationService::sendToAdmins(
            'new_order',
            'New order '.$orderNumber,
            '₱'.number_format($grandTotal, 2).' from '.$customer->full_name.' — waiting for payment.',
            'orders:'.$order->id
        );

        return redirect()->route('orders.gcash', $order->id);
    }
}
