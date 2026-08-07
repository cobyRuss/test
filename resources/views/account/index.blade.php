@extends('layouts.app')

@section('title', 'My Account | HappyStem')

@section('content')
    <section class="page-heading">
        <h2>My Account</h2>
        <p>Manage your profile and track your orders.</p>
    </section>

    <section style="padding: 20px 0 80px;">
        <div class="container">
            @if (session('profile_updated'))
                <div class="alert alert-success">Profile updated successfully!</div>
            @endif

            <div style="display:flex;gap:30px;flex-wrap:wrap;align-items:flex-start;">
                <div style="flex:1;min-width:300px;background:#fff;border-radius:12px;padding:25px;box-shadow:0 8px 25px rgba(0,0,0,0.08);">
                    <h3 style="color:var(--secondary);margin-bottom:15px;">Profile Information</h3>
                    <p><strong>Name:</strong> {{ $customer->full_name }}</p>
                    <p style="margin-top:6px;"><strong>Email:</strong> {{ $customer->email }}</p>
                    <p style="margin-top:6px;"><strong>Phone:</strong> {{ $customer->phone }}</p>
                    <p style="margin-top:6px;"><strong>Address:</strong> {{ $customer->address }}</p>

                    <hr style="border:none;border-top:1px solid #eee;margin:20px 0;">

                    <h4 style="color:var(--secondary);margin-bottom:15px;">Update Contact Details</h4>
                    <form action="{{ route('account.updateProfile') }}" method="POST">
                        @csrf
                        <div class="form-group" style="margin-bottom:15px;">
                            <label>Phone Number</label>
                            <input type="text" name="phone" value="{{ $customer->phone }}" required>
                        </div>
                        <div class="form-group" style="margin-bottom:15px;">
                            <label>Full Address</label>
                            <input type="text" name="address" value="{{ $customer->address }}" required>
                        </div>
                        <button type="submit" class="submit-btn"><i class="fas fa-save"></i> Save Changes</button>
                    </form>
                </div>

                <div style="flex:1.4;min-width:320px;">
                    <div style="background:#fff;border-radius:12px;padding:25px;box-shadow:0 8px 25px rgba(0,0,0,0.08);">
                        <h3 style="color:var(--secondary);margin-bottom:15px;">My Orders ({{ $orders->count() }})</h3>

                        @if ($orders->isEmpty())
                            <p style="color:var(--dark);">You haven't placed any orders yet.</p>
                            <a href="{{ route('products.index') }}" class="btn" style="margin-top:15px;">Start Shopping</a>
                        @else
                            @foreach ($orders as $order)
                                <a href="{{ route('orders.show', $order->id) }}" style="display:block;text-decoration:none;color:inherit;padding:15px 0;border-bottom:1px solid #f0f0f0;">
                                    <div style="display:flex;justify-content:space-between;align-items:center;">
                                        <div>
                                            <strong style="color:var(--dark);">#{{ $order->order_number }}</strong>
                                            <p style="font-size:0.85rem;color:var(--secondary);">{{ $order->created_at->format('M j, Y') }}</p>
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
                            <a href="{{ route('account.orders') }}" style="display:block;text-align:center;margin-top:15px;color:var(--secondary);font-weight:600;text-decoration:none;">
                                View All Orders &rarr;
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
