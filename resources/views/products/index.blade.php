@extends('layouts.app')

@section('title', 'Shop Flowers | HappyStem')

@section('content')
    <section class="page-heading" style="background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('{{ asset('images/bg2.jpg') }}') center/cover; color:#fff;">
        <h2>Our Flower Shop</h2>
        <p>Fresh, handcrafted bouquets for every moment worth celebrating.</p>
    </section>

    <section style="padding: 40px 0;">
        <div class="container">
            <div class="filters">
                <button class="filter-btn {{ $category === 'all' ? 'active' : '' }}" data-filter="all">All</button>
                @foreach ($categories as $cat)
                    <button class="filter-btn {{ $category === $cat->slug ? 'active' : '' }}" data-filter="{{ $cat->slug }}">{{ $cat->display_name }}</button>
                @endforeach
            </div>

            @if ($search !== '')
                <p class="category-name">Showing results for "{{ $search }}" ({{ $totalItems }} items)</p>
            @elseif ($category !== 'all')
                <p class="category-name">{{ $category }} Collection ({{ $totalItems }} items)</p>
            @endif

            @if ($products->isEmpty())
                <p style="text-align:center;color:var(--dark);padding:40px 0;">No flowers found. Try a different search or category.</p>
            @else
                <div class="catalogue">
                    @foreach ($products as $product)
                        <div class="product-card">
                            <div class="product-img">
                                <img src="{{ asset('images/'.$product->image_url) }}" alt="{{ $product->name }}" loading="lazy">
                            </div>
                            <div class="product-info">
                                <h3>{{ $product->name }}</h3>
                                <p>{{ \Illuminate\Support\Str::limit($product->description, 80) }}</p>
                                <div class="product-price">₱{{ number_format($product->price, 2) }}</div>
                                <div class="product-actions">
                                    <button class="add-to-cart-btn" data-id="{{ $product->id }}" data-name="{{ $product->name }}">
                                        <i class="fas fa-cart-plus"></i> Add
                                    </button>
                                    <button class="buy-now-btn" data-id="{{ $product->id }}">
                                        <i class="fas fa-bolt"></i> Buy Now
                                    </button>
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
