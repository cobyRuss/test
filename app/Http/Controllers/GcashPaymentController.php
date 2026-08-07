<?php

namespace App\Http\Controllers;

use App\Models\GcashPayment;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'payment_type' => 'down_payment',
            'screenshot_path' => $screenshot,
            'verified' => false,
        ]);

        $order->update(['payment_status' => 'partial']);

        $success = 'Payment submitted successfully! Our team will verify your payment within 24 hours.';

        return view('orders.gcash', compact('order', 'success'));
    }
}
