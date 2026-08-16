<meta data-order-number="{{ $order->order_number }}">

<style>
    .hs-od-grid { display: flex; gap: 26px; flex-wrap: wrap; }
    .hs-od-col { flex: 1; min-width: 320px; }
    .hs-od-block { margin-bottom: 20px; }
    .hs-od-block h4 { color: var(--secondary); margin: 0 0 12px; font-size: 0.98rem; display: flex; align-items: center; gap: 8px; }
    .hs-od-block h4 i { color: var(--accent); }
    .hs-od-row { font-size: 0.9rem; margin-bottom: 6px; }
    .hs-od-bubble { background: var(--light); border-radius: 10px; padding: 12px 14px; margin-bottom: 10px; }
    .hs-od-bubble p { font-size: 0.88rem; margin: 0; white-space: pre-wrap; }
    .hs-od-bubble .lbl { font-size: 0.8rem; font-weight: 700; color: var(--accent); margin-bottom: 4px; }
    .hs-od-total { display: flex; justify-content: space-between; font-size: 1.1rem; font-weight: 700; color: var(--dark); }
    .hs-od-total span:last-child { color: var(--accent); }
</style>

<div class="hs-od-grid">
    <div class="hs-od-col">
        <div class="hs-od-block">
            <h4><i class="fas fa-box"></i> Items</h4>
            <table class="admin-table">
                <thead><tr><th>Item</th><th>Qty</th><th>Price</th><th>Total</th></tr></thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td>
                                {{ $item->product_name }}
                                @if (! empty($item->description))
                                    <div style="font-size:0.78rem;color:#8a8a8a;margin-top:3px;white-space:pre-wrap;">{{ $item->description }}</div>
                                @endif
                            </td>
                            <td>{{ $item->quantity }}</td>
                            <td>₱{{ number_format($item->price, 2) }}</td>
                            <td>₱{{ number_format($item->price * $item->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="hs-od-block">
            <h4><i class="fas fa-receipt"></i> Totals</h4>
            <p class="hs-od-row">Delivery Fee: ₱{{ number_format($order->delivery_fee, 2) }}</p>
            <hr style="border:none;border-top:1px solid #eee;margin:10px 0;">
            <p class="hs-od-total"><span>Total</span><span>₱{{ number_format($order->total_amount, 2) }}</span></p>
        </div>

        <div class="hs-od-block">
            <h4><i class="fas fa-credit-card"></i> Payment</h4>
            <p class="hs-od-row"><strong>Method:</strong> {{ $order->payment_method === 'cod' ? 'Cash on Delivery' : strtoupper($order->payment_method) }}</p>
            <p class="hs-od-row">
                <strong>Status:</strong>
                <span class="badge {{ in_array($order->payment_status, ['completed', 'delivered']) ? 'badge-delivered' : ($order->payment_status === 'partial' ? 'badge-confirmed' : 'badge-pending') }}">
                    {{ $paymentLabels[$order->payment_status] ?? str_replace('_', ' ', $order->payment_status) }}
                </span>
            </p>
            @if ($order->payment_status === 'pending_downpayment')
                <p class="hs-od-row"><strong>Amount due:</strong> ₱{{ number_format($order->down_payment, 2) }}</p>
            @endif
            @if ($gcashPayment)
                <div style="background:var(--light);border-radius:10px;padding:12px;margin-top:10px;">
                    <p style="font-size:0.85rem;margin-bottom:4px;"><strong>GCash Ref:</strong> {{ $gcashPayment->reference_number }}</p>
                    <p style="font-size:0.85rem;margin-bottom:8px;"><strong>Status:</strong> {{ $gcashPayment->verified ? '✅ Verified' : '⏳ Awaiting verification' }}</p>
                    @if ($gcashPayment->screenshot_path)
                        <img src="{{ asset($gcashPayment->screenshot_path) }}" alt="GCash screenshot"
                             style="width:60px;height:60px;object-fit:cover;border-radius:8px;cursor:pointer;border:1px solid #ddd;"
                             onclick="openGcashLightbox('{{ asset($gcashPayment->screenshot_path) }}', '{{ $order->order_number }}');"
                             title="View GCash screenshot">
                    @endif
                </div>
            @endif
        </div>
    </div>

    <div class="hs-od-col">
        <div class="hs-od-block">
            <h4><i class="fas fa-user"></i> Sender</h4>
            <p class="hs-od-row">
                <strong>{{ $order->customer->full_name }}</strong>
                @if ($order->sender_anonymous)
                    <span class="badge" style="background:#d17b88;color:#fff;">Anonymous</span>
                @endif
            </p>
            <p class="hs-od-row">{{ $order->customer->email }}</p>
            @if ($order->sender_phone)
                <p class="hs-od-row">{{ $order->sender_phone }}</p>
            @endif
        </div>

        <div class="hs-od-block">
            <h4><i class="fas fa-map-marker-alt"></i> Recipient &amp; Delivery</h4>
            <p class="hs-od-row"><strong>{{ $order->recipient_name ?? $order->customer->full_name }}</strong></p>
            @if ($order->recipient_phone)
                <p class="hs-od-row">{{ $order->recipient_phone }}</p>
            @endif
            <p class="hs-od-row">{{ $order->delivery_address }}</p>
            <p class="hs-od-row">Municipality: {{ $order->municipality }}</p>
            <p class="hs-od-row">Delivery Date: {{ $order->delivery_date->format('F j, Y') }}</p>
        </div>

        @if ($order->message_for_recipient)
            <div class="hs-od-bubble">
                <p class="lbl"><i class="fas fa-envelope"></i> Message for recipient</p>
                <p>{{ $order->message_for_recipient }}</p>
            </div>
        @endif

        @if ($order->special_instructions)
            <div class="hs-od-bubble">
                <p class="lbl"><i class="fas fa-clipboard-list"></i> Special instructions</p>
                <p>{{ $order->special_instructions }}</p>
            </div>
        @endif
    </div>
</div>
