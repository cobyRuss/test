@extends('layouts.app')

@section('title', 'Checkout | HappyStem')

@section('content')
    <section class="page-heading">
        <h2>Checkout</h2>
        <p>Complete your delivery details and choose a payment method.</p>
    </section>

    <section style="padding: 30px 0 80px;">
        <div class="container" style="max-width: 900px;">
            @if ($errors->any())
                <div class="alert alert-error">
                    <ul style="margin-left:18px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div style="display:flex;gap:30px;flex-wrap:wrap;align-items:flex-start;">
                <form action="{{ route('checkout.store') }}" method="POST" style="flex:1.4;min-width:320px;background:#fff;border-radius:12px;padding:25px;box-shadow:0 8px 25px rgba(0,0,0,0.08);">
                    @csrf

                    <h3 style="color:var(--secondary);margin-bottom:20px;">Delivery Details</h3>

                    <div class="form-group" style="margin-bottom:15px;">
                        <label>Recipient Name</label>
                        <input type="text" value="{{ $customer->full_name }}" disabled>
                    </div>

                    <div class="form-group" style="margin-bottom:15px;">
                        <label>Municipality</label>
                        <select name="municipality" id="municipality" required>
                            @foreach ($deliveryFees as $muni => $fee)
                                <option value="{{ $muni }}" @selected(old('municipality', $selectedMunicipality) === $muni)>{{ $muni }} (₱{{ number_format($fee, 2) }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" style="margin-bottom:15px;">
                        <label>Barangay &amp; Street Address</label>
                        <input type="text" name="street" value="{{ old('street', $customer->address) }}" placeholder="e.g. Zone 3, Brgy. Zone 1, Street..." required>
                    </div>

                    <div class="form-group" style="margin-bottom:15px;">
                        <label>Delivery Date</label>
                        <input type="date" name="delivery_date" value="{{ old('delivery_date', now()->addDay()->toDateString()) }}" min="{{ now()->addDay()->toDateString() }}" required>
                    </div>

                    <div class="form-group" style="margin-bottom:20px;">
                        <label>Special Instructions (optional)</label>
                        <textarea name="special_instructions" rows="3" placeholder="e.g. Leave at the gate, call upon arrival...">{{ old('special_instructions') }}</textarea>
                    </div>

                    <h3 style="color:var(--secondary);margin-bottom:15px;">Payment Method</h3>

                    <div style="display:flex;gap:15px;flex-wrap:wrap;margin-bottom:20px;">
                        <label style="flex:1;border:2px solid #eee;border-radius:10px;padding:15px;cursor:pointer;display:flex;align-items:center;gap:10px;">
                            <input type="radio" name="payment_method" value="cod" @checked(old('payment_method', 'cod') === 'cod')>
                            <div>
                                <strong style="color:var(--dark);">Cash on Delivery</strong>
                                <p style="font-size:0.82rem;color:var(--secondary);">Pay the full amount upon delivery.</p>
                            </div>
                        </label>
                        <label style="flex:1;border:2px solid #eee;border-radius:10px;padding:15px;cursor:pointer;display:flex;align-items:center;gap:10px;">
                            <input type="radio" name="payment_method" value="gcash" @checked(old('payment_method') === 'gcash')>
                            <div>
                                <strong style="color:var(--dark);">GCash</strong>
                                <p style="font-size:0.82rem;color:var(--secondary);">Pay 50% down payment now.</p>
                            </div>
                        </label>
                    </div>

                    <button type="submit" class="btn" style="width:100%;text-align:center;">
                        <i class="fas fa-check-circle"></i> Place Order
                    </button>
                </form>

                <div style="flex:1;min-width:300px;">
                    <div style="background:#fff;border-radius:12px;padding:25px;box-shadow:0 8px 25px rgba(0,0,0,0.08);position:sticky;top:90px;">
                        <h3 style="color:var(--secondary);margin-bottom:15px;">Order Summary</h3>

                        @foreach ($items as $item)
                            <div style="display:flex;justify-content:space-between;font-size:0.9rem;margin-bottom:8px;">
                                <span>{{ $item['name'] }} &times; {{ $item['quantity'] }}</span>
                                <span>₱{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                            </div>
                        @endforeach

                        <hr style="border:none;border-top:1px solid #eee;margin:15px 0;">
                        <p style="display:flex;justify-content:space-between;"><span>Subtotal</span><span>₱{{ number_format($subtotal, 2) }}</span></p>
                        <p style="display:flex;justify-content:space-between;margin-top:8px;" id="deliveryFeeRow">
                            <span>Delivery</span><span>₱{{ number_format($deliveryFee, 2) }}</span>
                        </p>
                        <hr style="border:none;border-top:1px solid #eee;margin:15px 0;">
                        <p style="display:flex;justify-content:space-between;font-size:1.2rem;font-weight:700;color:var(--dark);">
                            <span>Total</span><span id="grandTotal" style="color:var(--accent);">₱{{ number_format($grandTotal, 2) }}</span>
                        </p>
                        <p style="display:flex;justify-content:space-between;margin-top:12px;font-size:0.95rem;">
                            <span>50% Down Payment (GCash)</span><span id="downPayment" style="color:var(--secondary);font-weight:600;">₱{{ number_format($downPayment, 2) }}</span>
                        </p>
                        <p style="display:flex;justify-content:space-between;margin-top:6px;font-size:0.95rem;">
                            <span>Remaining Balance</span><span id="remaining" style="color:var(--secondary);font-weight:600;">₱{{ number_format($remaining, 2) }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    const subtotal = {{ $subtotal }};
    const fees = @json($deliveryFees);

    document.getElementById('municipality').addEventListener('change', function() {
        const fee = parseFloat(fees[this.value] || 0);
        const total = subtotal + fee;
        document.querySelector('#deliveryFeeRow span:last-child').textContent = '₱' + total.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}).replace('₱', '₱');
        document.querySelector('#deliveryFeeRow span:last-child').textContent = '₱' + fee.toFixed(2);
        document.getElementById('grandTotal').textContent = '₱' + total.toFixed(2);
        document.getElementById('downPayment').textContent = '₱' + (total * 0.5).toFixed(2);
        document.getElementById('remaining').textContent = '₱' + (total * 0.5).toFixed(2);
    });
</script>
@endpush
