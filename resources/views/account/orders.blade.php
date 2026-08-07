@extends('layouts.app')

@section('title', 'My Orders | HappyStem')

@section('content')
    <section class="page-heading">
        <h2>My Orders</h2>
        <p>Track the status of all your HappyStem orders.</p>
    </section>

    <section style="padding: 20px 0 80px;">
        <div class="container" style="max-width: 820px;">
            <div style="background:#fff;border-radius:12px;padding:25px;box-shadow:0 8px 25px rgba(0,0,0,0.08);">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:15px;">
                    <h3 style="color:var(--secondary);">Orders ({{ $orders->count() }})</h3>
                    <a href="{{ route('account') }}" style="color:var(--secondary);font-weight:600;text-decoration:none;">&larr; Back to Account</a>
                </div>

                @if ($orders->isEmpty())
                    <p style="color:var(--dark);">You haven't placed any orders yet.</p>
                    <a href="{{ route('products.index') }}" class="btn" style="margin-top:15px;">Start Shopping</a>
                @else
                    @foreach ($orders as $order)
                        <a href="{{ route('orders.show', $order->id) }}" style="display:block;text-decoration:none;color:inherit;padding:15px 0;border-bottom:1px solid #f0f0f0;">
                            <div style="display:flex;justify-content:space-between;align-items:center;">
                                <div>
                                    <strong style="color:var(--dark);">#{{ $order->order_number }}</strong>
                                    <p style="font-size:0.85rem;color:var(--secondary);">{{ $order->created_at->format('F j, Y g:i A') }}</p>
                                </div>
                                <div style="text-align:right;">
                                    <span style="font-weight:700;color:var(--accent);">₱{{ number_format($order->total_amount, 2) }}</span>
                                    <p>
                                        <span style="font-size:0.8rem;padding:3px 10px;border-radius:20px;background:var(--light);color:var(--secondary);font-weight:600;">
                                            {{ ucfirst($order->order_status) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                @endif
            </div>
        </div>
    </section>
@endsection
