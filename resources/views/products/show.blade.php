@extends('layouts.app')

@section('title', $product->name.' | HappyStem')

@section('content')
    <section style="padding: 50px 0;">
        <div class="container">
            <nav style="margin-bottom:30px;font-size:0.9rem;">
                <a href="{{ route('home') }}" style="color:var(--accent);text-decoration:none;">Home</a>
                &raquo;
                <a href="{{ route('products.index') }}" style="color:var(--accent);text-decoration:none;">Shop</a>
                &raquo;
                <span>{{ $product->name }}</span>
            </nav>

            <div style="display:flex;gap:40px;flex-wrap:wrap;background:#fff;border-radius:12px;padding:30px;box-shadow:0 8px 25px rgba(0,0,0,0.08);">
                <div style="flex:1;min-width:300px;">
                    <div class="product-img {{ $product->is_available ? '' : 'is-unavailable' }}" style="height:auto;cursor:zoom-in;border-radius:10px;position:relative;">
                        <img src="{{ asset('images/'.$product->image_url) }}" alt="{{ $product->name }}" style="height:420px;">
                        @if (! $product->is_available)
                            <div class="stock-overlay">Not available at the moment</div>
                        @endif
                    </div>
                </div>
                <div style="flex:1;min-width:300px;">
                    <p style="color:var(--secondary);font-weight:600;text-transform:uppercase;font-size:0.85rem;">{{ $product->categories->pluck('display_name')->join(' & ') }}</p>
                    <h2 style="color:var(--dark);margin:10px 0;font-size:2rem;">{{ $product->name }}</h2>
                    <div class="product-price" style="font-size:1.6rem;margin:15px 0;">₱{{ number_format($product->price, 2) }}</div>
                    <p style="color:var(--dark);margin-bottom:25px;">{{ $product->description }}</p>

                    <div class="product-actions" style="margin-bottom:20px;">
                        @if ($product->is_available)
                            <button class="add-to-cart-btn" data-id="{{ $product->id }}" data-name="{{ $product->name }}">
                                <i class="fas fa-cart-plus"></i> Add to Cart
                            </button>
                            <button class="buy-now-btn" data-id="{{ $product->id }}">
                                <i class="fas fa-bolt"></i> Buy Now
                            </button>
                        @else
                            <span class="unavailable-btn"><i class="fas fa-exclamation-triangle"></i> Unavailable at the moment</span>
                        @endif
                    </div>

                    <div style="border-top:1px solid #eee;padding-top:15px;font-size:0.9rem;color:var(--dark);">
                        <p><i class="fas fa-truck" style="color:var(--secondary);"></i> Delivery available across Abra (delivery fee applies).</p>
                        <p style="margin-top:8px;"><i class="fas fa-credit-card" style="color:var(--secondary);"></i> Pay via GCash or Cash on Delivery.</p>
                        <p style="margin-top:8px;"><i class="fas fa-tag" style="color:var(--secondary);"></i> Freshly arranged on the day of delivery.</p>
                    </div>
                </div>
            </div>

            @if ($related->isNotEmpty())
                <h3 class="section-title" style="margin-top:60px;">You May Also Like</h3>
                <div class="catalogue">
                    @foreach ($related as $item)
                        <div class="product-card {{ $item->is_available ? '' : 'is-unavailable' }}">
                            <a href="{{ route('products.show', $item->id) }}" style="text-decoration:none;color:inherit;">
                                <div class="product-img">
                                    <img src="{{ asset('images/'.$item->image_url) }}" alt="{{ $item->name }}" loading="lazy">
                                    @if (! $item->is_available)
                                        <div class="stock-overlay">Not available at the moment</div>
                                    @endif
                                </div>
                            </a>
                            <div class="product-info">
                                <h3>{{ $item->name }}</h3>
                                <div class="product-price">₱{{ number_format($item->price, 2) }}</div>
                                <div class="product-actions">
                                    @if ($item->is_available)
                                        <button class="add-to-cart-btn" data-id="{{ $item->id }}" data-name="{{ $item->name }}">
                                            <i class="fas fa-cart-plus"></i> Add
                                        </button>
                                    @else
                                        <span class="unavailable-btn"><i class="fas fa-exclamation-triangle"></i></span>
                                    @endif
                                    <a class="buy-now-btn" href="{{ route('products.show', $item->id) }}" style="text-decoration:none;">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
