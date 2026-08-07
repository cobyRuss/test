<?php

namespace App\Http\Controllers;

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
