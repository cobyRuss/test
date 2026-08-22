<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\GcashPayment;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GcashPaymentController extends Controller
{
    public function show(Request $request, int $orderId)
    {
        $order = Order::query()
            ->where('id', $orderId)
            ->where('customer_id', Auth::guard('web')->id())
            ->firstOrFail();

        return view('orders.gcash', compact('order'))->with('success', '');
    }

    public function store(Request $request, int $orderId)
    {
        $order = Order::query()
            ->where('id', $orderId)
            ->where('customer_id', Auth::guard('web')->id())
            ->firstOrFail();

        $data = $request->validate([
            'reference_number' => ['required'],
            'screenshot' => ['required', 'image', 'max:5120'],
        ]);

        $referenceNumber = trim($data['reference_number']);
        $screenshot = null;

        if ($request->hasFile('screenshot') && $request->file('screenshot')->isValid()) {
            $file = $request->file('screenshot');
            $filename = 'gcash_'.$orderId.'_'.time().'.'.$file->extension();

            $uploadDir = public_path('uploads/gcash');
            if (! is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $file->move($uploadDir, $filename);
            $screenshot = 'uploads/gcash/'.$filename;
        }

        GcashPayment::query()->create([
            'order_id' => $order->id,
            'reference_number' => $referenceNumber,
            'amount' => $order->down_payment,
            'payment_type' => 'full_payment',
            'screenshot_path' => $screenshot,
            'verified' => false,
        ]);

        $order->update(['payment_status' => 'partial']);

        NotificationService::sendToAdmins(
            'payment_pending',
            'Payment pending verification',
            'GCash payment (₱'.number_format($order->down_payment, 2).') submitted for '.$order->order_number.'.',
            'payments:'.$order->id
        );

        $success = 'Payment submitted successfully! Our team will verify your payment within 24 hours.';

        return view('orders.gcash', compact('order', 'success'));
    }

    public function cancel(int $orderId)
    {
        $order = Order::query()
            ->where('id', $orderId)
            ->where('customer_id', Auth::guard('web')->id())
            ->where('order_status', 'pending')
            ->firstOrFail();

        DB::transaction(function () use ($order) {
            $items = OrderItem::where('order_id', $order->id)->get();

            foreach ($items as $item) {
                if ($item->product_id > 0) {
                    $existing = CartItem::where('customer_id', $order->customer_id)
                        ->where('product_id', $item->product_id)
                        ->first();

                    if ($existing) {
                        $existing->increment('quantity', $item->quantity);
                    } else {
                        CartItem::create([
                            'customer_id' => $order->customer_id,
                            'product_id' => $item->product_id,
                            'quantity' => $item->quantity,
                        ]);
                    }
                }
            }

            $order->update(['order_status' => 'cancelled', 'cancel_reason' => 'Payment not completed']);
            $items->each->delete();
        });

        return redirect()->route('cart.index')->with('message', 'Order cancelled. Your items have been restored to your cart.');
    }
}
