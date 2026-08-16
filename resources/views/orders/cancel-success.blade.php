@extends('layouts.app')

@section('title', 'Order Cancelled | HappyStem')

@section('content')
    <section class="page-heading">
        <h2>Order Cancelled</h2>
    </section>

    <section style="padding: 20px 0 80px;">
        <div class="container" style="max-width: 640px;">
            <div style="background:#fff;border-radius:12px;padding:35px;box-shadow:0 8px 25px rgba(0,0,0,0.08);text-align:center;">
                <i class="fas fa-check-circle" style="font-size:4rem;color:var(--secondary);"></i>
                <h3 style="color:var(--dark);margin:15px 0;">Your order has been cancelled successfully.</h3>
                <p style="color:var(--secondary);margin-bottom:10px;">Order #{{ $cancelled['order_number'] }}</p>

                <div style="text-align:left;background:var(--light);border-radius:8px;padding:15px;margin:20px 0;">
                    <h4 style="color:var(--secondary);margin-bottom:10px;">Cancelled Items</h4>
                    @foreach ($cancelled['items'] as $item)
                        <div style="font-size:0.92rem;padding:4px 0;">
                            <div style="display:flex;justify-content:space-between;">
                                <span>{{ $item['product_name'] }} &times; {{ $item['quantity'] }}</span>
                                <span>₱{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                            </div>
                            @if (! empty($item['description']))
                                <p style="font-size:0.8rem;color:var(--secondary);margin-top:2px;">{{ $item['description'] }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>

                <p style="font-size:0.88rem;color:var(--dark);margin-bottom:20px;">
                    Any GCash payment will be refunded within 3–5 business days. For questions,
                    please contact us at happystem.bangued@gmail.com.
                </p>

                <a href="{{ route('products.index') }}" class="btn"><i class="fas fa-store"></i> Continue Shopping</a>
            </div>
        </div>
    </section>
@endsection
