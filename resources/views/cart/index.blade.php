@extends('layouts.app')

@section('title', 'Your Cart | HappyStem')

@section('content')
    <style>
        .qty-stepper { display: inline-flex; align-items: center; border: 1px solid #ddd; border-radius: 6px; overflow: hidden; }
        .qty-stepper .qty-btn { width: 30px; height: 34px; border: none; background: #f7f3f4; color: var(--dark); font-size: 1rem; cursor: pointer; }
        .qty-stepper .qty-btn:hover { background: var(--primary); color: #fff; }
        .qty-stepper .qty-input { width: 52px; height: 34px; border: none; border-left: 1px solid #ddd; border-right: 1px solid #ddd; text-align: center; font-size: 0.88rem; -moz-appearance: textfield; }
        .qty-stepper .qty-input::-webkit-inner-spin-button, .qty-stepper .qty-input::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
        .qty-warning { flex-basis: 100%; color: #b3261e; font-size: 0.78rem; font-weight: 600; display: none; }
    </style>

    <section class="page-heading">
        <h2>Your Cart</h2>
        <p>Review your items and proceed to checkout.</p>
    </section>

    <section style="padding: 30px 0 80px;">
        <div class="container">

            @auth('web')
                @if (session('cart_error'))
                    <div style="background:#fdecea;color:#b3261e;border:1px solid #f5c6c2;border-radius:8px;padding:12px 16px;margin-bottom:18px;font-size:0.9rem;">{{ session('cart_error') }}</div>
                @endif
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
                                        <form action="{{ route('cart.update') }}" method="POST" class="cart-update-form" style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                                            @csrf
                                            <input type="hidden" name="cart_id" value="{{ $item['cart_id'] }}">
                                            @if ($item['custom'])
                                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" style="width:70px;padding:8px;border:1px solid #ddd;border-radius:6px;">
                                            @else
                                                <div class="qty-stepper" data-max="20">
                                                    <button type="button" class="qty-btn qty-minus" aria-label="Decrease quantity">&minus;</button>
                                                    <input type="number" name="quantity" class="qty-input" value="{{ $item['quantity'] }}" min="1" max="20">
                                                    <button type="button" class="qty-btn qty-plus" aria-label="Increase quantity">+</button>
                                                </div>
                                                <div class="qty-warning">(!) Sorry, the maximum value is reached</div>
                                            @endif
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

@push('scripts')
<script>
    document.querySelectorAll('.qty-stepper').forEach(stepper => {
        const input = stepper.querySelector('.qty-input');
        const form = stepper.closest('.cart-update-form');
        const warning = form ? form.querySelector('.qty-warning') : null;
        const max = parseInt(stepper.dataset.max, 10) || 20;

        let warningTimer;

        function showWarning() {
            if (!warning) return;
            warning.style.display = 'block';
            clearTimeout(warningTimer);
            warningTimer = setTimeout(() => { warning.style.display = 'none'; }, 2500);
        }

        stepper.querySelector('.qty-minus').addEventListener('click', () => {
            const val = parseInt(input.value, 10) || 1;
            input.value = Math.max(1, val - 1);
        });

        stepper.querySelector('.qty-plus').addEventListener('click', () => {
            const val = parseInt(input.value, 10) || 1;
            if (val >= max) {
                input.value = max;
                showWarning();
                return;
            }
            input.value = val + 1;
        });

        input.addEventListener('change', () => {
            let val = parseInt(input.value, 10);
            if (isNaN(val) || val < 1) {
                val = 1;
            }
            if (val > max) {
                input.value = max;
                showWarning();
            } else {
                input.value = val;
            }
        });
    });
</script>
@endpush
