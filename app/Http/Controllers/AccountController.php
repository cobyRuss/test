<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Notification;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index()
    {
        $customer = Auth::guard('web')->user();

        $orders = Order::query()
            ->where('customer_id', $customer->id)
            ->orderByDesc('created_at')
            ->get();

        return view('account.index', compact('customer', 'orders'));
    }

    public function orders()
    {
        $customer = Auth::guard('web')->user();

        $orders = Order::query()
            ->where('customer_id', $customer->id)
            ->orderByDesc('created_at')
            ->get();

        return view('account.orders', compact('customer', 'orders'));
    }

    public function notifications()
    {
        $customer = Auth::guard('web')->user();

        $notifications = Notification::query()
            ->forCustomer($customer->id)
            ->orderByDesc('created_at')
            ->paginate(20);

        Notification::query()->forCustomer($customer->id)->where('is_read', false)->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return view('account.notifications', compact('customer', 'notifications'));
    }

    public function messages()
    {
        $customer = Auth::guard('web')->user();

        $messages = ContactMessage::query()
            ->where(function ($q) use ($customer) {
                $q->where('customer_id', $customer->id)->orWhere('email', $customer->email);
            })
            ->orderByDesc('id')
            ->paginate(20);

        return view('account.messages', compact('customer', 'messages'));
    }

    public function updateProfile(Request $request)
    {
        $customer = Auth::guard('web')->user();

        $data = $request->validate([
            'phone' => ['required'],
            'address' => ['required'],
        ]);

        $customer->update([
            'phone' => $data['phone'],
            'address' => $data['address'],
        ]);

        session(['profile_updated' => true]);

        return redirect()->route('account');
    }
}
