@extends('layouts.app')

@section('title', 'GCash Payment | HappyStem')

@section('content')
    <section class="page-heading">
        <h2>GCash Payment</h2>
        <p>Order #{{ $order->order_number }}</p>
    </section>

    <section style="padding: 20px 0 80px;">
        <div class="container" style="max-width: 640px;">
            @if ($success)
                <div class="alert alert-success">{{ $success }}</div>
                <div style="background:#fff;border-radius:12px;padding:25px;box-shadow:0 8px 25px rgba(0,0,0,0.08);text-align:center;">
                    <p style="color:var(--dark);margin-bottom:15px;">Your down payment of <strong style="color:var(--accent);">₱{{ number_format($order->down_payment, 2) }}</strong> will be verified by our team within 24 hours.</p>
                    <a href="{{ route('orders.show', $order->id) }}" class="btn"><i class="fas fa-eye"></i> View Order</a>
                </div>
            @else
                <div style="background:#fff;border-radius:12px;padding:25px;box-shadow:0 8px 25px rgba(0,0,0,0.08);margin-bottom:20px;">
                    <h3 style="color:var(--secondary);margin-bottom:10px;">GCash Details</h3>
                    <p style="font-size:0.95rem;color:var(--dark);">
                        Send your <strong>50% down payment</strong> of
                        <strong style="color:var(--accent);">₱{{ number_format($order->down_payment, 2) }}</strong>
                        to the GCash number below, then fill in your payment details.
                    </p>
                    <div style="background:var(--light);border-radius:8px;padding:15px;margin-top:15px;">
                        <p><strong>GCash Number:</strong> <span style="color:var(--accent);font-weight:700;">0917-123-4567</span></p>
                        <p><strong>Account Name:</strong> HappyStem</p>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="alert alert-error">
                        <ul style="margin-left:18px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('orders.gcash.submit', $order->id) }}" method="POST" enctype="multipart/form-data" style="background:#fff;border-radius:12px;padding:25px;box-shadow:0 8px 25px rgba(0,0,0,0.08);">
                    @csrf
                    <div class="form-group" style="margin-bottom:15px;">
                        <label>GCash Reference Number</label>
                        <input type="text" name="reference_number" value="{{ old('reference_number') }}" placeholder="e.g. 1234567890" required>
                    </div>
                    <div class="form-group" style="margin-bottom:20px;">
                        <label>Screenshot of Payment (optional)</label>
                        <input type="file" name="screenshot" accept="image/*">
                        <p style="font-size:0.82rem;color:var(--secondary);margin-top:5px;">Attach a screenshot to help us verify faster.</p>
                    </div>
                    <button type="submit" class="btn" style="width:100%;text-align:center;"><i class="fas fa-check-circle"></i> Submit Payment</button>
                </form>
            @endif
        </div>
    </section>
@endsection
