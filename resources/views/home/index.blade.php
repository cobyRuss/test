@extends('layouts.app')

@section('title', 'HappyStem | Flower Shop & Delivery in Bangued, Abra')

@section('content')
    <section class="hero" style="background: linear-gradient(rgba(0,0,0,0.35), rgba(0,0,0,0.35)), url('{{ asset('images/bg.jpg') }}') center/cover;">
        <div class="hero-content">
            <h2>Fresh Flowers, Delivered with Love</h2>
            <p>Handcrafted bouquets and arrangements for every occasion, delivered fresh across Abra.</p>
            <div style="display:flex;gap:15px;justify-content:center;flex-wrap:wrap;">
                <a href="#catalogue" class="btn">Shop Flowers</a>
                <a href="{{ route('customize.index') }}" class="btnn">Customize Your Own</a>
            </div>
        </div>
    </section>

    <section id="catalogue" class="catalogue-section" style="padding: 40px 0;">
        <div class="container">
            <h2 class="section-title">Our Flower Catalogue</h2>

            <div class="filters">
                <button class="filter-btn {{ $category === 'all' ? 'active' : '' }}" data-filter="all">All</button>
                @foreach ($categories as $cat)
                    <button class="filter-btn {{ $category === $cat ? 'active' : '' }}" data-filter="{{ $cat }}">{{ ucfirst($cat) }}</button>
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

    <section class="contact" id="contact" style="padding: 80px 0; background: var(--light);">
        <div class="container" style="max-width: 760px;">
            <h2 class="section-title">Get in Touch</h2>
            <p style="text-align:center;margin-bottom:30px;color:var(--dark);">
                Have a question or want to place a special order? Send us a message!
            </p>

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

            <form class="contact-form" action="{{ route('contact.send') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Your full name">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="you@email.com">
                </div>
                <div class="form-group">
                    <label>Message</label>
                    <textarea name="message" rows="6" placeholder="How can we help you?">{{ old('message') }}</textarea>
                </div>
                <button type="submit" class="submit-btn" style="align-self:flex-start;"><i class="fas fa-paper-plane"></i> Send Message</button>
            </form>
        </div>
    </section>
@endsection
