<?php

namespace App\Http\Controllers;

use App\Models\GcashPayment;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    private function findOrder(int $id): Order
    {
        $query = Order::query()->where('id', $id);

        if (! Auth::guard('admin')->check()) {
            $query->where('customer_id', Auth::guard('web')->id());
        }

        return $query->firstOrFail();
    }

    public function show(Request $request, int $id)
    {
        $order = $this->findOrder($id);
        $items = OrderItem::query()->where('order_id', $order->id)->get();
        $gcashPayment = GcashPayment::query()->where('order_id', $order->id)->first();

        $statusLabels = [
            'pending' => ['Pending', '⏳ Your order is waiting for confirmation'],
            'confirmed' => ['Confirmed', '✅ Your order has been confirmed'],
            'preparing' => ['Preparing', '🌸 We are preparing your beautiful flowers'],
            'ready' => ['Ready for Delivery', '🚚 Your order is ready for delivery'],
            'delivered' => ['Delivered', '🎉 Your order has been delivered! Thank you!'],
            'cancelled' => ['Cancelled', '❌ This order has been cancelled'],
        ];

        $paymentLabels = [
            'pending_downpayment' => 'Unpaid',
            'partial' => 'Payment Submitted',
            'completed' => 'Paid',
            'pending_cod' => 'COD',
        ];

        return view('orders.show', compact('order', 'items', 'gcashPayment', 'statusLabels', 'paymentLabels'));
    }

    public function cancelForm(Request $request, int $id)
    {
        $order = $this->findOrder($id);

        if ($order->order_status !== 'pending') {
            return redirect()->route('account');
        }

        $orderItems = OrderItem::query()->where('order_id', $order->id)->get();

        $cancellationReasons = [
            'Change of delivery address',
            'Change of contact number',
            "I don't want the item any more",
            'I decided for alternative product',
            'Change payment method',
            'Want to place a new order with more/different items',
            'Delivery time is too long',
            'Duplicated order',
            'Other reason',
        ];

        return view('orders.cancel', compact('order', 'orderItems', 'cancellationReasons'));
    }

    public function cancel(Request $request, int $id)
    {
        $order = $this->findOrder($id);

        if ($order->order_status !== 'pending') {
            return redirect()->route('account');
        }

        $orderItems = OrderItem::query()->where('order_id', $order->id)->get();

        $reason = trim((string) $request->input('reason'));
        $note = trim((string) $request->input('note'));
        $agreed = $request->boolean('agreed');

        if (empty($reason)) {
            return back()->withErrors(['cancel' => 'Please select a reason for cancellation.']);
        }

        if (! $agreed) {
            return back()->withErrors(['cancel' => 'Please read and accept the Cancellation Policy before proceeding.']);
        }

        $order->update([
            'order_status' => 'cancelled',
            'cancel_reason' => $reason,
            'cancel_note' => $note,
            'cancelled_at' => now(),
        ]);

        NotificationService::sendToAdmins(
            'order_cancelled',
            'Order cancelled by customer',
            'Order '.$order->order_number.' was cancelled ('.$reason.').',
            'orders:'.$order->id
        );

        session([
            'cancelled_order' => [
                'order_number' => $order->order_number,
                'items' => $orderItems->toArray(),
            ],
        ]);

        return redirect()->route('orders.cancel-success');
    }

    public function cancelSuccess()
    {
        $cancelled = session('cancelled_order');

        if (! $cancelled) {
            return redirect()->route('account');
        }

        session()->forget('cancelled_order');

        return view('orders.cancel-success', compact('cancelled'));
    }
}
