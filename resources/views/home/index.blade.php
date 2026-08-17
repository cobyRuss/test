@extends('layouts.app')

@section('title', 'HappyStem | Flower Shop & Delivery in Bangued, Abra')

@section('content')
    <section class="hero" id="home" style="background: linear-gradient(rgba(232, 180, 188, 0.8), rgba(138, 155, 110, 0.8)), url('{{ asset('images/bg2.jpg') }}') center/cover;">
        <div class="hero-content">
            <h2>Beautiful Flowers for Every Occasion</h2>
            <p>Discover the beauty of nature with our handpicked selection of fresh flowers and arrangements. Bring joy and elegance to your space with our floral creations.</p>
            <a href="#catalogue" class="btnn">Explore Our Collection</a>
        </div>
    </section>

    <section id="catalogue" class="catalogue-section" style="padding: 40px 0;">
        <div class="container">
            <h2 class="section-title">Our Flower Catalogue</h2>

            <div class="filters">
                <button class="filter-btn {{ $category === 'all' ? 'active' : '' }}" data-filter="all">All</button>
                @foreach ($categories as $cat)
                    @php
                        $avail = $categoryAvailability[$cat] ?? ['total' => 0, 'available' => 0];
                        $catUnavailable = $avail['total'] > 0 && $avail['available'] === 0;
                    @endphp
                    <button class="filter-btn {{ $category === $cat ? 'active' : '' }} {{ $catUnavailable ? 'cat-unavailable' : '' }}" data-filter="{{ $cat }}">
                        {{ ucfirst($cat) }} @if ($catUnavailable)<small> (Unavailable)</small>@endif
                    </button>
                @endforeach
            </div>

            @if ($search !== '')
                <p class="category-name">Showing results for "{{ $search }}" ({{ $totalItems }} items)</p>
            @elseif ($category !== 'all')
                <p class="category-name">{{ ucfirst($category) }} Collection ({{ $totalItems }} items)</p>
            @endif

            @if ($products->isEmpty())
                <p style="text-align:center;color:var(--dark);padding:40px 0;">No flowers found. Try a different search or category.</p>
            @else
                <div class="catalogue">
                    @foreach ($products as $product)
                        <div class="product-card {{ $product->is_available ? '' : 'is-unavailable' }}" data-href="{{ route('products.show', $product->id) }}">
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
                                @if ($product->review_count > 0)
                                    <div style="color:#f5a623;font-size:0.8rem;">@for ($i = 1; $i <= 5; $i)<i class="fas fa-star{{ $i <= round($product->average_rating) ? '' : '-o' }}"></i>@endfor <span style="color:#aaa;">({{ $product->review_count }})</span></div>
                                @endif
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
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="pagination">
                    @for ($i = 1; $i <= $totalPages; $i++)
                        <a href="{{ route('home', array_filter(['category' => $category !== 'all' ? $category : null, 'search' => $search !== '' ? $search : null, 'page' => $i])) }}"
                           class="pagination-btn {{ $i === $page ? 'active' : '' }}">{{ $i }}</a>
                    @endfor
                </div>
            @endif
        </div>
    </section>

    <section class="services" id="services">
        <div class="container">
            <h2 class="section-title">Our Services</h2>
            <p class="services-intro">
                From weddings to corporate events, we provide premium floral services across Abra.
                <a href="{{ route('services.index') }}" style="color:var(--accent);font-weight:600;">View all services &rarr;</a>
            </p>

            <div class="services-grid">
                @php
                    $homeServices = [
                        ['weddings', 'fa-rings-wedding', 'Weddings & Debuts', 'Bridal bouquets, church flowers, reception centerpieces and full venue styling for your big day.'],
                        ['events', 'fa-calendar-check', 'Events & Celebrations', 'Birthdays, anniversaries, pageants and parties — custom florals for any celebration.'],
                        ['corporate', 'fa-building', 'Corporate & Business', 'Office lobby arrangements, conference flowers and grand opening stands.'],
                        ['sympathy', 'fa-dove', 'Sympathy & Condolences', 'Dignified funeral wreaths, stands, sprays and condolence baskets.'],
                        ['romance', 'fa-heart', 'Love & Romance', "Valentine's specials, proposal arrangements and anniversary surprises."],
                        ['getwell', 'fa-face-smile', 'Get Well & Cheer', 'Hospital deliveries, get well baskets and cheer-up arrangements.'],
                    ];
                @endphp

                @foreach ($homeServices as [$key, $icon, $title, $desc])
                    <div class="service-card">
                        <div class="service-header">
                            <div class="service-icon"><i class="fas {{ $icon }}"></i></div>
                            <h3>{{ $title }}</h3>
                        </div>
                        <div class="service-content">
                            <p style="margin-bottom:15px;font-size:0.92rem;">{{ $desc }}</p>
                            <button class="photos-btn" data-service="{{ $key }}">
                                <i class="fas fa-images"></i> View Photos
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="about" id="about">
        <div class="container">
            <div class="about-content">
                <div class="about-text">
                    <h2>About HappyStem</h2>
                    <p style="margin-bottom:15px;">
                        HappyStem is a locally owned flower shop in Bangued, Abra, dedicated to bringing joy through fresh,
                        beautifully arranged flowers. From our hands to your doorsteps, we take pride in every bloom we craft.
                    </p>
                    <p style="margin-bottom:25px;">
                        Whether it's a wedding, a simple gesture of love, or a corporate event, we treat every order with the
                        care and attention it deserves.
                    </p>
                    <a href="{{ route('products.index') }}" class="btn"><i class="fas fa-store"></i> Visit Our Shop</a>
                </div>
                <div class="about-image">
                    <img src="{{ asset('images/aboutus.jpg') }}" alt="About HappyStem" class="shop-image">
                </div>
            </div>
        </div>
    </section>

            @if ($contactSuccess)
                <div class="alert alert-success">{{ $contactSuccess }}</div>
            @endif

            @if ($contactErrors)
                <div class="alert alert-error">
                    <ul style="margin-left:18px;">
                        @foreach ($contactErrors as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </section>
@endsection
