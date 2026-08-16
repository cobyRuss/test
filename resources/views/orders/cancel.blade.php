@extends('layouts.app')

@section('title', 'Cancel Order | HappyStem')

@section('content')
    <section class="page-heading">
        <h2>Cancel Order #{{ $order->order_number }}</h2>
    </section>

    <section style="padding: 20px 0 80px;">
        <div class="container" style="max-width: 720px;">
            @if ($errors->any())
                <div class="alert alert-error">
                    <ul style="margin-left:18px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div style="background:#fff;border-radius:12px;padding:25px;box-shadow:0 8px 25px rgba(0,0,0,0.08);margin-bottom:25px;">
                <h3 style="color:var(--secondary);margin-bottom:15px;">Order Items</h3>
                @foreach ($orderItems as $item)
                    <div style="padding:8px 0;border-bottom:1px solid #f0f0f0;font-size:0.92rem;">
                        <div style="display:flex;justify-content:space-between;">
                            <span>{{ $item->product_name }} &times; {{ $item->quantity }}</span>
                            <span>₱{{ number_format($item->price * $item->quantity, 2) }}</span>
                        </div>
                        @if (! empty($item->description))
                            <p style="font-size:0.8rem;color:var(--secondary);margin-top:4px;">{{ $item->description }}</p>
                        @endif
                    </div>
                @endforeach
                <p style="display:flex;justify-content:space-between;margin-top:12px;font-weight:700;">
                    <span>Total</span><span style="color:var(--accent);">₱{{ number_format($order->total_amount, 2) }}</span>
                </p>
            </div>

            <form action="{{ route('orders.cancel.submit', $order->id) }}" method="POST" style="background:#fff;border-radius:12px;padding:25px;box-shadow:0 8px 25px rgba(0,0,0,0.08);">
                @csrf

                <h3 style="color:var(--secondary);margin-bottom:15px;">Cancellation Policy</h3>
                <div style="background:var(--light);border-radius:8px;padding:15px;margin-bottom:20px;font-size:0.9rem;color:var(--dark);">
                    <ul style="margin-left:18px;">
                        <li>Cancellation is only allowed while the order is still pending confirmation.</li>
                        <li>GCash payments are refunded within 3–5 business days.</li>
                        <li>Once an order is confirmed or prepared, it can no longer be cancelled.</li>
                    </ul>
                </div>

                <div class="form-group" style="margin-bottom:15px;">
                    <label>Reason for Cancellation</label>
                    <select name="reason" required>
                        <option value="">-- Select a reason --</option>
                        @foreach ($cancellationReasons as $reason)
                            <option value="{{ $reason }}" @selected(old('reason') === $reason)>{{ $reason }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" style="margin-bottom:15px;">
                    <label>Additional Notes (optional)</label>
                    <textarea name="note" rows="3" placeholder="Any additional details...">{{ old('note') }}</textarea>
                </div>

                <label style="display:flex;align-items:flex-start;gap:10px;font-size:0.9rem;color:var(--dark);margin-bottom:20px;">
                    <input type="checkbox" name="agreed" value="1" style="margin-top:4px;" @checked(old('agreed'))>
                    <span>I have read and accept the Cancellation Policy.</span>
                </label>

                <div style="display:flex;gap:12px;">
                    <button type="submit" class="btnn" style="background:#c94a4a;"><i class="fas fa-times-circle"></i> Confirm Cancellation</button>
                    <a href="{{ route('orders.show', $order->id) }}" class="btn" style="background:#aaa;">Keep Order</a>
                </div>
            </form>
        </div>
    </section>
@endsection
