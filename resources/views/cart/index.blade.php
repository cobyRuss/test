@extends('layouts.app')

@section('title', 'Your Cart | HappyStem')

@section('content')
    <section class="page-heading">
        <h2>Your Cart</h2>
        <p>Review your items and proceed to checkout.</p>
    </section>

    <section style="padding: 30px 0 80px;">
        <div class="container">

            @auth('web')
                @if (empty($items))
                    <div style="text-align:center;padding:50px 0;">
                        <i class="fas fa-shopping-cart" style="font-size:3rem;color:var(--primary);"></i>
                        <p style="margin:20px 0;font-size:1.1rem;color:var(--dark);">Your cart is empty.</p>
                        <a href="{{ route('products.index') }}" class="btn">Browse Flowers</a>
                    </div>
                @else
                    <div style="display:flex;gap:30px;flex-wrap:wrap;align-items:flex-start;">
                        <div style="flex:1.4;min-width:320px;">
                            <div style="background:#fff;border-radius:12px;padding:25px;box-shadow:0 8px 25px rgba(0,0,0,0.08);">
                                @foreach ($items as $item)
                                    <div style="display:flex;gap:15px;padding:15px 0;border-bottom:1px solid #f0f0f0;align-items:center;">
                                        <div style="width:80px;height:80px;border-radius:8px;overflow:hidden;flex-shrink:0;">
                                            @if ($item['image_url'])
                                                <img src="{{ asset('images/'.$item['image_url']) }}" alt="{{ $item['name'] }}" style="width:100%;height:100%;object-fit:cover;">
                                            @else
                                                <div style="width:100%;height:100%;background:var(--primary);display:flex;align-items:center;justify-content:center;color:#fff;">
                                                    <i class="fas fa-seedling"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div style="flex:1;">
                                            <strong style="color:var(--dark);">{{ $item['name'] }}</strong>
                                            @if ($item['custom'] && ! empty($item['description']))
                                                <p style="font-size:0.82rem;color:var(--secondary);">{{ $item['description'] }}</p>
                                            @endif
                                            <p style="color:var(--accent);font-weight:600;">₱{{ number_format($item['price'], 2) }}</p>
                                        </div>
                                        <form action="{{ route('cart.update') }}" method="POST" style="display:flex;align-items:center;gap:8px;">
                                            @csrf
                                            <input type="hidden" name="cart_id" value="{{ $item['cart_id'] }}">
                                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" style="width:70px;padding:8px;border:1px solid #ddd;border-radius:6px;">
                                            <button type="submit" class="submit-btn" style="padding:8px 14px;font-size:0.82rem;">Update</button>
                                        </form>
                                        <form action="{{ route('cart.remove') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="cart_id" value="{{ $item['cart_id'] }}">
                                            <button type="submit" style="background:none;border:none;color:#b3261e;cursor:pointer;font-size:1.1rem;" title="Remove">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div style="flex:1;min-width:300px;">
                            <div style="background:#fff;border-radius:12px;padding:25px;box-shadow:0 8px 25px rgba(0,0,0,0.08);">
                                <h3 style="color:var(--secondary);margin-bottom:15px;">Order Summary</h3>
                                <p style="display:flex;justify-content:space-between;"><span>Subtotal</span><span>₱{{ number_format($subtotal, 2) }}</span></p>
                                <p style="display:flex;justify-content:space-between;margin-top:8px;"><span>Delivery ({{ $municipality }})</span><span>₱{{ number_format($deliveryFee, 2) }}</span></p>
                                <hr style="border:none;border-top:1px solid #eee;margin:15px 0;">
                                <p style="display:flex;justify-content:space-between;font-size:1.2rem;font-weight:700;color:var(--dark);">
                                    <span>Total</span><span style="color:var(--accent);">₱{{ number_format($grandTotal, 2) }}</span>
                                </p>
                                <a href="{{ route('checkout.index') }}" class="btn" style="width:100%;text-align:center;margin-top:20px;">
                                    <i class="fas fa-check-circle"></i> Proceed to Checkout
                                </a>
                                <a href="{{ route('products.index') }}" style="display:block;text-align:center;margin-top:12px;color:var(--secondary);font-weight:600;text-decoration:none;">
                                    &larr; Continue Shopping
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <div style="text-align:center;padding:50px 0;">
                    <i class="fas fa-user-lock" style="font-size:3rem;color:var(--primary);"></i>
                    <p style="margin:20px 0;font-size:1.1rem;color:var(--dark);">Please login to view your cart and place an order.</p>
                    <div style="display:flex;gap:15px;justify-content:center;">
                        <a href="{{ route('login') }}" class="btn">Login</a>
                        <a href="{{ route('register') }}" class="btnn">Register</a>
                    </div>
                </div>
            @endauth
        </div>
    </section>
@endsection
