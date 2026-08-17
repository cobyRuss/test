@extends('layouts.app')

@section('title', 'Checkout | HappyStem')

@section('content')
    <style>
        .hs-checkout-wrap { max-width: 980px; margin: 30px auto 80px; padding: 0 20px; }
        .hs-checkout-grid { display: flex; gap: 26px; flex-wrap: wrap; align-items: flex-start; }
        .hs-checkout-form { flex: 1.4; min-width: 320px; }
        .hs-checkout-side { flex: 1; min-width: 290px; }

        .hs-card { background: #fff; border-radius: 14px; padding: 24px; box-shadow: 0 8px 25px rgba(0,0,0,0.08); margin-bottom: 20px; }
        .hs-card h3 { color: var(--secondary); margin: 0 0 4px; font-size: 1.12rem; display: flex; align-items: center; gap: 9px; }
        .hs-card h3 i { color: var(--accent); }
        .hs-card .hs-card-sub { color: var(--dark); font-size: 0.85rem; margin: 0 0 18px; }

        .hs-field { margin-bottom: 14px; display: flex; flex-direction: column; }
        .hs-field label { color: var(--dark); font-weight: 600; font-size: 0.86rem; margin-bottom: 6px; }
        .hs-field input,
        .hs-field select,
        .hs-field textarea { width: 100%; padding: 11px 13px; border: 1px solid #e5e5e5; border-radius: 10px; font-size: 0.93rem; background: #f9f9fb; color: var(--dark); transition: border-color 0.2s, box-shadow 0.2s; }
        .hs-field input:focus,
        .hs-field select:focus,
        .hs-field textarea:focus { outline: none; border-color: var(--accent); background: #fff; box-shadow: 0 0 0 3px rgba(209, 123, 136, 0.15); }
        .hs-field input::placeholder,
        .hs-field textarea::placeholder { color: #b5b0b0; }
        .hs-field input:disabled { background: #f1efef; color: #8a8a8a; }

        .hs-row { display: grid; grid-template-columns: 1fr 1fr; gap: 13px; }

        .hs-phone-wrap { display: flex; border: 1px solid #e5e5e5; border-radius: 10px; overflow: hidden; background: #f9f9fb; }
        .hs-phone-wrap:focus-within { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(209, 123, 136, 0.15); }
        .hs-phone-prefix { display: flex; align-items: center; gap: 6px; background: #f1efef; padding: 0 12px; font-size: 0.9rem; font-weight: 600; color: var(--dark); white-space: nowrap; border-right: 1px solid #e5e5e5; }
        .hs-phone-prefix img { width: 20px; height: 14px; border-radius: 2px; object-fit: cover; }
        .hs-phone-wrap input { border: none; border-radius: 0; background: transparent; }

        .hs-option-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 18px; }
        .hs-option-btn { display: flex; flex-direction: column; align-items: flex-start; gap: 4px; text-align: left; padding: 15px 16px; border: 2px solid #e5e5e5; border-radius: 12px; background: #fff; cursor: pointer; font-family: inherit; transition: all 0.2s; }
        .hs-option-btn strong { color: var(--dark); font-size: 0.93rem; }
        .hs-option-btn span { color: #8a8a8a; font-size: 0.78rem; }
        .hs-option-btn.active { border-color: var(--accent); background: #fdf7f8; box-shadow: 0 0 0 3px rgba(209, 123, 136, 0.12); }
        .hs-option-btn.active strong { color: var(--accent); }

        .hs-recipient-block { border-left: 3px solid var(--accent); padding-left: 16px; margin-bottom: 4px; }

        .hs-msg-count { font-size: 0.78rem; color: #8a8a8a; text-align: right; margin-top: 4px; }
        .hs-msg-count.over { color: #b3261e; font-weight: 600; }
        .hs-note { font-size: 0.78rem; color: #8a8a8a; margin-top: 5px; line-height: 1.5; }
        .hs-hint { font-size: 0.8rem; color: var(--secondary); margin: -8px 0 14px; line-height: 1.5; }

        .hs-check { display: flex; align-items: flex-start; gap: 9px; margin-top: 4px; cursor: pointer; }
        .hs-check input { width: 16px; height: 16px; margin-top: 3px; accent-color: var(--accent); flex-shrink: 0; }
        .hs-check span { font-size: 0.9rem; color: var(--dark); }

        .hs-pay-option { border: 2px solid #0ec68f; border-radius: 12px; padding: 15px; display: flex; align-items: center; gap: 10px; background: #f6fdfa; margin-bottom: 14px; }
        .hs-pay-option strong { color: var(--dark); }
        .hs-pay-option p { font-size: 0.8rem; color: var(--secondary); margin: 0; }
        .hs-gcash-info { background: var(--light); border-radius: 10px; padding: 13px 15px; margin-bottom: 18px; font-size: 0.9rem; }

        .hs-checkout-btn { width: 100%; display: inline-flex; align-items: center; justify-content: center; gap: 9px; background: linear-gradient(120deg, var(--accent), #c96a78); color: #fff; border: none; padding: 15px; border-radius: 30px; font-size: 1rem; font-weight: 700; cursor: pointer; box-shadow: 0 6px 18px rgba(209, 123, 136, 0.4); transition: var(--transition); }
        .hs-checkout-btn:hover { background: var(--secondary); transform: translateY(-2px); }

        .hs-summary-row { display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 8px; }
        .hs-summary-total { display: flex; justify-content: space-between; font-size: 1.2rem; font-weight: 700; color: var(--dark); }
        .hs-summary-total span:last-child { color: var(--accent); }

        .hs-flag { width: 20px; height: 14px; border-radius: 2px; object-fit: cover; }

        @media (max-width: 700px) {
            .hs-row { grid-template-columns: 1fr; }
            .hs-option-row { grid-template-columns: 1fr; }
        }
    </style>

    <section class="hs-checkout-wrap">
        @if ($errors->any())
            <div class="alert alert-error" style="max-width:980px;">
                <ul style="margin-left:18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @php
            $senderPhone = old('sender_phone', (string) ($customer->phone ?? ''));
        @endphp

        <div class="hs-checkout-grid">
            <form action="{{ route('checkout.store') }}" method="POST" class="hs-checkout-form">
                @csrf

                <div class="hs-card">
                    <h3><i class="fas fa-user"></i> Sender Information</h3>
                    <p class="hs-card-sub">Who is sending this order?</p>

                    <div class="hs-field">
                        <label>Email Address</label>
                        <input type="email" value="{{ $customer->email }}" disabled>
                    </div>

                    <div class="hs-row">
                        <div class="hs-field">
                            <label>First Name</label>
                            <input type="text" name="sender_first_name" value="{{ old('sender_first_name', $customer->first_name) }}" required>
                        </div>
                        <div class="hs-field">
                            <label>Last Name</label>
                            <input type="text" name="sender_last_name" value="{{ old('sender_last_name', $customer->last_name) }}" required>
                        </div>
                    </div>

                    <div class="hs-field">
                        <label>Phone Number</label>
                        <div class="hs-phone-wrap">
                            <span class="hs-phone-prefix"><img src="https://flagcdn.com/w40/ph.png" alt="" class="hs-flag"> +63</span>
                            <input type="tel" name="sender_phone" value="{{ old('sender_phone', $senderPhone) }}" placeholder="9171234567" maxlength="10" inputmode="numeric" class="hs-phone-input" required>
                        </div>
                        <p class="hs-note">Enter your 10-digit mobile number.</p>
                    </div>
                </div>

                <div class="hs-card">
                    <h3><i class="fas fa-gift"></i> Recipient Information</h3>
                    <p class="hs-card-sub">Who will receive this order?</p>

                    <div class="hs-option-row">
                        <button type="button" class="hs-option-btn active" data-mode="me">
                            <strong>I'll receive the order</strong>
                            <span>Deliver to me</span>
                        </button>
                        <button type="button" class="hs-option-btn" data-mode="someone_else">
                            <strong>Someone else will receive it</strong>
                            <span>Deliver to another person</span>
                        </button>
                    </div>

                    <div class="hs-recipient-block" id="recipientBlock" style="display:none;">
                        <div class="hs-row">
                            <div class="hs-field">
                                <label>Recipient First Name</label>
                                <input type="text" name="recipient_first_name" value="{{ old('recipient_first_name') }}" placeholder="First name">
                            </div>
                            <div class="hs-field">
                                <label>Recipient Last Name</label>
                                <input type="text" name="recipient_last_name" value="{{ old('recipient_last_name') }}" placeholder="Last name">
                            </div>
                        </div>
                        <div class="hs-field">
                            <label>Recipient Phone Number</label>
                            <div class="hs-phone-wrap">
                                <span class="hs-phone-prefix"><img src="https://flagcdn.com/w40/ph.png" alt="" class="hs-flag"> +63</span>
                                <input type="tel" name="recipient_phone" value="{{ old('recipient_phone') }}" placeholder="9171234567" maxlength="10" inputmode="numeric" class="hs-phone-input">
                            </div>
                        </div>
                    </div>

                    <div class="hs-field">
                        <label>Municipality</label>
                        <select name="municipality" id="municipality" required>
                            @foreach ($deliveryFees as $muni => $fee)
                                <option value="{{ $muni }}" @selected(old('municipality', $selectedMunicipality) === $muni)>{{ $muni }} (₱{{ number_format($fee, 2) }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="hs-row">
                        <div class="hs-field">
                            <label>Barangay</label>
                            <input type="text" name="barangay" value="{{ old('barangay', $customer->municipality ?? '') }}" placeholder="e.g. Zone 4" required>
                        </div>
                        <div class="hs-field">
                            <label>Street / Landmark</label>
                            <input type="text" name="street" value="{{ old('street', $customer->address) }}" placeholder="e.g. Arellano St., near ABC" required>
                        </div>
                    </div>

                    <div class="hs-field" style="margin-bottom:0;">
                        <label>Delivery Date</label>
                        <input type="date" name="delivery_date" value="{{ old('delivery_date', now()->addDay()->toDateString()) }}" min="{{ now()->addDay()->toDateString() }}" required>
                    </div>
                </div>

                <div class="hs-card">
                    <h3><i class="fas fa-comment-dots"></i> Message &amp; Instructions</h3>

                    <div class="hs-field">
                        <label>Optional: Message for recipient</label>
                        <textarea name="message_for_recipient" id="recipientMessage" rows="3" maxlength="400" placeholder="Remember to tell who you are if you want the recipient to know.">{{ old('message_for_recipient') }}</textarea>
                        <div class="hs-msg-count" id="msgCount">0/400</div>
                    </div>

                    <div class="hs-field">
                        <label>Optional: Special instructions for the rider/merchant</label>
                        <p class="hs-hint">Please understand that we cannot promise any exact hour for your delivery.</p>
                        <textarea name="special_instructions" rows="3" placeholder="(e.g. who to contact upon arrival, alternative number, landmark, color of door/gate, details for personalized product)">{{ old('special_instructions') }}</textarea>
                    </div>

                    <label class="hs-check">
                        <input type="checkbox" name="sender_anonymous" value="1" @checked(old('sender_anonymous'))>
                        <span><strong>Yes, I want to make the sender anonymous.</strong></span>
                    </label>
                </div>

                <div class="hs-card">
                    <h3><i class="fas fa-credit-card"></i> Payment Method</h3>
                    <p class="hs-card-sub">GCash is the only payment option for now.</p>

                    <div class="hs-pay-option">
                        <input type="radio" name="payment_method" value="gcash" checked>
                        <div>
                            <strong>GCash</strong>
                            <p>Pay the full amount (100%) via GCash before delivery.</p>
                        </div>
                    </div>

                    <div class="hs-gcash-info">
                        <p style="margin-bottom:4px;"><strong>Send payment to:</strong></p>
                        <p><span style="color:var(--accent);font-weight:700;">{{ config('happystem.gcash_number') }}</span> — {{ config('happystem.gcash_account_name') }} (GCash)</p>
                        <p style="font-size:0.8rem;color:var(--secondary);margin-top:4px;">You'll submit the reference number and screenshot right after placing the order.</p>
                    </div>

                    <button type="submit" class="hs-checkout-btn">
                        <i class="fas fa-arrow-right"></i> Go to Payment
                    </button>
                </div>
            </form>

            <div class="hs-checkout-side">
                <div class="hs-card" style="position:sticky;top:90px;">
                    <h3><i class="fas fa-shopping-bag"></i> Order Summary</h3>

                    @foreach ($items as $item)
                        <div class="hs-summary-row">
                            <span>{{ $item['name'] }} &times; {{ $item['quantity'] }}</span>
                            <span>₱{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                        </div>
                    @endforeach

                    <hr style="border:none;border-top:1px solid #eee;margin:14px 0;">
                    <p class="hs-summary-row"><span>Subtotal</span><span>₱{{ number_format($subtotal, 2) }}</span></p>
                    <p class="hs-summary-row" id="deliveryFeeRow">
                        <span>Delivery</span><span>₱{{ number_format($deliveryFee, 2) }}</span>
                    </p>
                    <hr style="border:none;border-top:1px solid #eee;margin:14px 0;">
                    <p class="hs-summary-total">
                        <span>Total</span><span id="grandTotal">₱{{ number_format($grandTotal, 2) }}</span>
                    </p>
                    <p class="hs-summary-row" style="margin-top:12px;font-size:0.95rem;">
                        <span>GCash Payment (100%)</span><span id="gcashAmount" style="color:var(--secondary);font-weight:600;">₱{{ number_format($grandTotal, 2) }}</span>
                    </p>
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
        document.querySelector('#deliveryFeeRow span:last-child').textContent = '₱' + fee.toFixed(2);
        document.getElementById('grandTotal').textContent = '₱' + total.toFixed(2);
        document.getElementById('gcashAmount').textContent = '₱' + total.toFixed(2);
    });

    document.querySelectorAll('.hs-phone-input').forEach(function(input) {
        input.addEventListener('input', function() {
            let digits = this.value.replace(/\D/g, '');
            if (digits.startsWith('63') && digits.length > 10) digits = digits.slice(2);
            else if (digits.startsWith('0') && digits.length === 11) digits = digits.slice(1);
            digits = digits.slice(0, 10);
            if (digits && digits[0] !== '9') digits = '9' + digits.slice(0, 9);
            this.value = digits;
        });
    });

    const modeButtons = document.querySelectorAll('.hs-option-btn');
    const recipientBlock = document.getElementById('recipientBlock');
    const modeInput = document.createElement('input');
    modeInput.type = 'hidden';
    modeInput.name = 'recipient_mode';
    modeInput.value = 'me';
    recipientBlock.parentNode.insertBefore(modeInput, recipientBlock);

    function setRecipientMode(mode) {
        modeInput.value = mode;
        modeButtons.forEach(function(btn) {
            btn.classList.toggle('active', btn.dataset.mode === mode);
        });
        recipientBlock.style.display = mode === 'someone_else' ? 'block' : 'none';
        const required = mode === 'someone_else';
        recipientBlock.querySelectorAll('input[type="text"], input[type="tel"]').forEach(function(input) {
            input.required = required;
        });
    }

    modeButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            setRecipientMode(btn.dataset.mode);
        });
    });

    setRecipientMode(@json(old('recipient_mode', 'me')));

    const msg = document.getElementById('recipientMessage');
    const msgCount = document.getElementById('msgCount');
    function updateCount() {
        msgCount.textContent = msg.value.length + '/400';
        msgCount.classList.toggle('over', msg.value.length === 400);
    }
    msg.addEventListener('input', updateCount);
    updateCount();
</script>
@endpush
