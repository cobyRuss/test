@extends('layouts.app')

@section('title', 'Shop Flowers | HappyStem')

@section('content')
    <section class="page-heading" style="background: url('{{ asset('images/ra.jpg') }}') center/cover; color:#fff; padding:70px 20px;">
        <h2 style="color:#fff;text-shadow:1px 2px 5px rgba(0,0,0,0.6);">Our Flower Shop</h2>
    </section>

    <section style="padding: 40px 0;">
        <div class="container">
            <div class="filters">
                <button class="filter-btn {{ $category === 'all' ? 'active' : '' }}" data-filter="all">All</button>
                @foreach ($categories as $cat)
                    @php
                        $avail = $categoryAvailability[$cat->slug] ?? ['total' => 0, 'available' => 0];
                        $catUnavailable = $avail['total'] > 0 && $avail['available'] === 0;
                    @endphp
                    <button class="filter-btn {{ $category === $cat->slug ? 'active' : '' }} {{ $catUnavailable ? 'cat-unavailable' : '' }}" data-filter="{{ $cat->slug }}">
                        {{ $cat->display_name }} @if ($catUnavailable)<small> (Unavailable)</small>@endif
                    </button>
                @endforeach
            </div>

            @if ($search !== '')
                <p class="category-name">Showing results for "{{ $search }}" ({{ $totalItems }} items)</p>
            @elseif ($category !== 'all')
                <p class="category-name">{{ optional($categories->firstWhere('slug', $category))->display_name ?? $category }} Collection ({{ $totalItems }} items)</p>
            @endif

            @if ($products->isEmpty())
                <p style="text-align:center;color:var(--dark);padding:40px 0;">No flowers found. Try a different search or category.</p>
            @else
                <div class="catalogue">
                    @foreach ($products as $product)
                        <div class="product-card {{ $product->is_available ? '' : 'is-unavailable' }}">
                            <div class="product-img">
                                <img src="{{ asset('images/'.$product->image_url) }}" alt="{{ $product->name }}" loading="lazy">
                                @if (! $product->is_available)
                                    <div class="stock-overlay">Not available at the moment</div>
                                @endif
                            </div>
                            <div class="product-info">
                                <h3>{{ $product->name }}</h3>
                                <p>{{ \Illuminate\Support\Str::limit($product->description, 80) }}</p>
                                <div class="product-price">₱{{ number_format($product->price, 2) }}</div>
                                <div class="product-actions">
                                    @if ($product->is_available)
                                        @auth('web')
                                            <button class="add-to-cart-btn" data-id="{{ $product->id }}" data-name="{{ $product->name }}">
                                                <i class="fas fa-cart-plus"></i> Add
                                            </button>
                                            <button class="buy-now-btn" data-id="{{ $product->id }}">
                                                <i class="fas fa-bolt"></i> Buy Now
                                            </button>
                                        @else
                                            <a href="{{ route('login', ['redirect' => url()->current()]) }}" class="add-to-cart-btn">
                                                <i class="fas fa-sign-in-alt"></i> Login to Order
                                            </a>
                                            <a href="{{ route('login', ['redirect' => url()->current()]) }}" class="buy-now-btn">
                                                <i class="fas fa-bolt"></i> Buy Now
                                            </a>
                                        @endauth
                                    @else
                                        <span class="unavailable-btn"><i class="fas fa-exclamation-triangle"></i> Unavailable</span>
                                    @endif
                                    <a class="buy-now-btn" href="{{ route('products.show', $product->id) }}" style="text-decoration:none;">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="pagination">
                    @for ($i = 1; $i <= $totalPages; $i++)
                        <a href="{{ route('products.index', array_filter(['category' => $category !== 'all' ? $category : null, 'search' => $search !== '' ? $search : null, 'page' => $i])) }}"
                           class="pagination-btn {{ $i === $page ? 'active' : '' }}">{{ $i }}</a>
                    @endfor
                </div>
            @endif
        </div>
    </section>
@endsection
