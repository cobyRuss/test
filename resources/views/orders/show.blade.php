@extends('layouts.app')

@section('title', 'Order '.$order->order_number.' | HappyStem')

@section('content')
    <section class="page-heading">
        <h2>Order #{{ $order->order_number }}</h2>
        <p>Placed on {{ $order->created_at->format('F j, Y g:i A') }}</p>
    </section>

    <section style="padding: 20px 0 80px;">
        <div class="container" style="max-width: 800px;">
            @if (session('profile_updated'))
                <div class="alert alert-success">Profile updated successfully!</div>
            @endif

            @php
                $status = $order->order_status;
                [$statusLabel, $statusMessage] = $statusLabels[$status] ?? ['Unknown', ''];
            @endphp

            <div style="background:#fff;border-radius:12px;padding:25px;box-shadow:0 8px 25px rgba(0,0,0,0.08);margin-bottom:25px;">
                <h3 style="color:var(--secondary);margin-bottom:10px;">{{ $statusLabel }}</h3>
                <p style="color:var(--dark);">{{ $statusMessage }}</p>
            </div>

            <div style="display:flex;gap:25px;flex-wrap:wrap;align-items:flex-start;">
                <div style="flex:1.2;min-width:300px;background:#fff;border-radius:12px;padding:25px;box-shadow:0 8px 25px rgba(0,0,0,0.08);">
                    <h3 style="color:var(--secondary);margin-bottom:15px;">Items</h3>
                    @foreach ($items as $item)
                        <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid #f0f0f0;font-size:0.92rem;">
                            <span>{{ $item->product_name }} &times; {{ $item->quantity }}</span>
                            <span style="color:var(--accent);font-weight:600;">₱{{ number_format($item->price * $item->quantity, 2) }}</span>
                        </div>
                    @endforeach
                    <p style="display:flex;justify-content:space-between;margin-top:12px;"><span>Delivery Fee</span><span>₱{{ number_format($order->delivery_fee, 2) }}</span></p>
                    <hr style="border:none;border-top:1px solid #eee;margin:12px 0;">
                    <p style="display:flex;justify-content:space-between;font-size:1.15rem;font-weight:700;color:var(--dark);">
                        <span>Total</span><span style="color:var(--accent);">₱{{ number_format($order->total_amount, 2) }}</span>
                    </p>

                    @if (in_array($order->payment_method, ['gcash', 'cod']))
                        <div style="margin-top:14px;background:#fdf7f8;border:1px solid #f0e0e3;border-radius:10px;padding:12px 14px;font-size:0.9rem;">
                            @if ($order->payment_status === 'pending_cod')
                                <p style="display:flex;justify-content:space-between;font-weight:700;color:var(--secondary);">
                                    <span>Payment</span><span>Payable on delivery</span>
                                </p>
                            @elseif ($order->payment_status === 'completed')
                                <p style="display:flex;justify-content:space-between;font-weight:700;color:var(--secondary);">
                                    <span>Payment</span><span>Fully Paid</span>
                                </p>
                            @else
                                <p style="display:flex;justify-content:space-between;">
                                    <span>{{ $order->payment_status === 'partial' ? 'Deposit Paid (GCash)' : 'Deposit Due (GCash, 50%)' }}</span>
                                    <span style="color:var(--secondary);font-weight:600;">₱{{ number_format($order->down_payment, 2) }}</span>
                                </p>
                                <p style="display:flex;justify-content:space-between;margin-top:6px;">
                                    <span>{{ $order->payment_status === 'partial' ? 'Remaining Balance (on delivery)' : 'Balance Due (on delivery)' }}</span>
                                    <span style="color:var(--secondary);font-weight:600;">₱{{ number_format($order->remaining_balance, 2) }}</span>
                                </p>
                            @endif
                        </div>
                    @endif
                </div>

                <div style="flex:1;min-width:280px;background:#fff;border-radius:12px;padding:25px;box-shadow:0 8px 25px rgba(0,0,0,0.08);">
                    <h3 style="color:var(--secondary);margin-bottom:15px;">Delivery &amp; Payment</h3>
                    <p style="font-size:0.92rem;margin-bottom:8px;"><strong>Address:</strong> {{ $order->delivery_address }}</p>
                    <p style="font-size:0.92rem;margin-bottom:8px;"><strong>Delivery Date:</strong> {{ $order->delivery_date->format('F j, Y') }}</p>
                    @if ($order->special_instructions)
                        <p style="font-size:0.92rem;margin-bottom:8px;"><strong>Instructions:</strong> {{ $order->special_instructions }}</p>
                    @endif
                    <p style="font-size:0.92rem;margin-bottom:8px;"><strong>Payment:</strong> {{ $order->payment_method === 'cod' ? 'Cash on Delivery' : strtoupper($order->payment_method) }}</p>
                    <p style="font-size:0.92rem;"><strong>Payment Status:</strong>
                        <span style="color:{{ $order->payment_status === 'completed' ? 'var(--secondary)' : 'var(--accent)' }};">
                            {{ $paymentLabels[$order->payment_status] ?? str_replace('_', ' ', strtoupper($order->payment_status)) }}
                        </span>
                    </p>

                    @if (in_array($order->payment_method, ['gcash', 'cod']) && $order->payment_status === 'pending_downpayment')
                        <a href="{{ route('orders.gcash', $order->id) }}" class="btn" style="width:100%;text-align:center;margin-top:18px;">
                            <i class="fas fa-money-bill-wave"></i> Pay Down Payment
                        </a>
                    @endif

                    @if (in_array($order->payment_method, ['gcash', 'cod']) && $order->payment_status === 'partial' && ! $gcashPayment)
                        <a href="{{ route('orders.gcash', $order->id) }}" class="btn" style="width:100%;text-align:center;margin-top:18px;">
                            <i class="fas fa-money-bill-wave"></i> Submit GCash Reference
                        </a>
                    @endif

                    @if ($gcashPayment)
                        <div style="margin-top:18px;background:var(--light);border-radius:8px;padding:12px;">
                            <p style="font-size:0.85rem;"><strong>GCash Ref:</strong> {{ $gcashPayment->reference_number }}</p>
                            <p style="font-size:0.85rem;"><strong>Status:</strong> {{ $gcashPayment->verified ? '✅ Verified' : '⏳ Awaiting verification' }}</p>
                            @if ($gcashPayment->screenshot_path)
                                <a href="{{ asset($gcashPayment->screenshot_path) }}" target="_blank" style="font-size:0.85rem;color:var(--accent);font-weight:600;">View screenshot</a>
                            @endif
                        </div>
                    @endif

                    @if ($order->order_status === 'pending')
                        <a href="{{ route('orders.cancel', $order->id) }}" class="btnn" style="width:100%;text-align:center;margin-top:18px;background:#c94a4a;">
                            <i class="fas fa-times-circle"></i> Cancel Order
                        </a>
                    @endif

                    <a href="{{ route('account') }}" style="display:block;text-align:center;margin-top:14px;color:var(--secondary);font-weight:600;text-decoration:none;">
                        &larr; Back to Account
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
