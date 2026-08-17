<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'HappyStem | Flower Shop & Delivery in Bangued, Abra')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ filemtime(public_path('css/style.css')) }}">
    <link rel="icon" href="{{ asset('images/qqq.png') }}" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        .page-heading { text-align: center; padding: 60px 20px 20px; }
        .page-heading h2 { color: var(--secondary); font-size: 2.2rem; }
        .page-heading p { color: var(--dark); max-width: 700px; margin: 10px auto 0; }
        .auth-wrap { max-width: 460px; margin: 40px auto 80px; padding: 0 20px; }
        .auth-box { background: #fff; border-radius: 12px; padding: 35px; box-shadow: 0 8px 25px rgba(0,0,0,0.08); }
        .auth-box h3 { color: var(--secondary); margin-bottom: 5px; }
        .auth-box .sub { color: var(--dark); font-size: 0.9rem; margin-bottom: 20px; }
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-size: 0.92rem; }
        .alert-error { background: #fdecea; color: #b3261e; border: 1px solid #f5c6c2; }
        .alert-success { background: #e8f5e9; color: #1e7a2c; border: 1px solid #b7e0bd; }
        .alert-info { background: #eef3fa; color: #1a4a8a; border: 1px solid #c5d8ef; }
        .auth-links { margin-top: 18px; text-align: center; font-size: 0.92rem; }
        .auth-links a { color: var(--accent); font-weight: 600; text-decoration: none; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; }
        .error-text { color: #b3261e; font-size: 0.82rem; margin-top: 4px; }
        .cart-link { color: #fff; text-decoration: none; font-weight: 600; font-size: 0.9rem; padding: 7px 12px; border-radius: 20px; background: rgba(255,255,255,0.18); white-space: nowrap; }
        .cart-link:hover { background: rgba(255,255,255,0.3); }
        .nav-auth { display: flex; align-items: center; gap: 8px; }
        .notif-bell { position: relative; }
        .notif-bell-btn { background: rgba(255,255,255,0.18); border: none; color: #fff; width: 36px; height: 36px; border-radius: 50%; cursor: pointer; position: relative; flex-shrink: 0; }
        .notif-bell-btn:hover { background: rgba(255,255,255,0.3); }
        .notif-badge { position: absolute; top: -4px; right: -4px; background: var(--accent); color: #fff; font-size: 0.62rem; font-weight: 700; min-width: 17px; height: 17px; border-radius: 10px; display: none; align-items: center; justify-content: center; padding: 0 4px; border: 2px solid #fff; line-height: 1; }
        .notif-badge.show { display: flex; }
        .notif-dropdown { display: none; position: absolute; top: 44px; right: 0; width: 330px; max-width: 92vw; background: #fff; border-radius: 12px; box-shadow: 0 8px 30px rgba(0,0,0,0.18); z-index: 200; overflow: hidden; }
        .notif-dropdown.show { display: block; }
        .notif-dropdown-head { display: flex; justify-content: space-between; align-items: center; padding: 12px 14px; border-bottom: 1px solid #f0f0f0; }
        .notif-dropdown-head strong { color: var(--dark); font-size: 0.9rem; }
        .notif-mark-all { background: none; border: none; color: var(--accent); font-size: 0.75rem; font-weight: 600; cursor: pointer; padding: 0; }
        .notif-mark-all:hover { text-decoration: underline; }
        .notif-list { max-height: 360px; overflow-y: auto; }
        .notif-item { display: flex; gap: 10px; padding: 11px 14px; border-bottom: 1px solid #f6f3f3; text-decoration: none; color: inherit; align-items: flex-start; }
        .notif-item:hover { background: #fdf7f8; }
        .notif-item .notif-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--accent); flex-shrink: 0; margin-top: 5px; }
        .notif-item.read .notif-dot { background: transparent; }
        .notif-item-title { font-size: 0.85rem; font-weight: 600; color: var(--dark); }
        .notif-item-body { font-size: 0.78rem; color: var(--secondary); margin-top: 1px; }
        .notif-item-time { font-size: 0.7rem; color: #8a8a8a; margin-top: 2px; }
        .notif-empty { text-align: center; padding: 26px 14px; color: #8a8a8a; font-size: 0.85rem; }
        .notif-view-all { display: block; text-align: center; padding: 10px; font-size: 0.8rem; font-weight: 600; color: var(--accent); text-decoration: none; border-top: 1px solid #f0f0f0; }
        .notif-view-all:hover { background: #fdf7f8; }
        @media (max-width: 820px) {
            .header-right { flex-direction: column; width: 100%; }
            .nav-auth { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <img src="{{ asset('images/qqq.png') }}" alt="HappyStem Logo" class="logo-img">
                    <div class="logo-text">
                        <h1>HappyStem</h1>
                        <p>by Carmencita</p>
                    </div>
                </div>

                <div class="header-right">
                    <nav>
                        <ul>
                            <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                            <li><a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}">Shop</a></li>
                            <li><a href="{{ route('services.index') }}" class="{{ request()->routeIs('services.*') ? 'active' : '' }}">Services</a></li>
                            <li><a href="{{ route('customize.index') }}" class="{{ request()->routeIs('customize.*') ? 'active' : '' }}">Customize</a></li>
                            <li><a href="#contact" class="{{ session('contact_success') || session('contact_errors') ? 'active' : '' }}">Contact</a></li>
                        </ul>
                    </nav>

                    <div class="nav-auth">
                        <div class="search-bar">
                            <form action="{{ route('products.index') }}" method="GET">
                                <input type="text" name="search" placeholder="Search flowers..." value="{{ request('search') }}">
                                <button type="submit" aria-label="Search"><i class="fas fa-search"></i></button>
                            </form>
                        </div>

                        <a href="{{ route('cart.index') }}" class="cart-link">
                            <i class="fas fa-shopping-cart"></i> Cart <span id="cartCount">{{ Auth::guard('web')->check() ? '' : '' }}</span>
                        </a>

                        @auth('web')
                            <a href="{{ route('account') }}" class="cart-link"><i class="fas fa-user"></i> {{ \Illuminate\Support\Str::before(Auth::guard('web')->user()->full_name, ' ') }}</a>
                            <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                                @csrf
                                <button type="submit" class="cart-link" style="border:none;cursor:pointer;"><i class="fas fa-sign-out-alt"></i> Logout</button>
                            </form>
                            <div class="notif-bell" id="customerNotifBell"
                                 data-unread-url="{{ route('notifications.unread') }}"
                                 data-read-url="{{ route('notifications.read') }}"
                                 data-view-all="{{ route('account.notifications') }}">
                                <button type="button" class="notif-bell-btn" aria-label="Notifications">
                                    <i class="fas fa-bell"></i>
                                    <span class="notif-badge" id="customerNotifBadge">0</span>
                                </button>
                                <div class="notif-dropdown" id="customerNotifDropdown">
                                    <div class="notif-dropdown-head">
                                        <strong>Notifications</strong>
                                        <button type="button" class="notif-mark-all" id="customerMarkAll">Mark all as read</button>
                                    </div>
                                    <div class="notif-list" id="customerNotifList"></div>
                                    <a href="{{ route('account.notifications') }}" class="notif-view-all">View all notifications</a>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="cart-link"><i class="fas fa-sign-in-alt"></i> Login</a>
                            <a href="{{ route('register') }}" class="cart-link"><i class="fas fa-user-plus"></i> Register</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer id="contact">
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>HappyStem</h3>
                    <p>Your trusted flower shop in Bangued, Abra. Fresh flowers for every occasion, delivered with love.</p>
                    <div class="social-links">
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="Messenger"><i class="fab fa-facebook-messenger"></i></a>
                    </div>
                </div>

                <div class="footer-column">
                    <h3>Quick Links</h3>
                    <div class="contact-info">
                        <a href="{{ route('products.index') }}" style="color:#fff;text-decoration:none;">Shop Flowers</a>
                        <a href="{{ route('services.index') }}" style="color:#fff;text-decoration:none;">Our Services</a>
                        <a href="{{ route('customize.index') }}" style="color:#fff;text-decoration:none;">Customize a Bouquet</a>
                        <a href="{{ route('account') }}" style="color:#fff;text-decoration:none;">My Account</a>
                    </div>
                </div>

                <div class="footer-column">
                    <h3>Contact Us</h3>
                    <div class="contact-info">
                        <div class="contact-item"><i class="fas fa-map-marker-alt"></i> Bangued, Abra, Philippines</div>
                        <div class="contact-item"><i class="fas fa-phone"></i> 0917-123-4567</div>
                        <div class="contact-item"><i class="fas fa-envelope"></i> happystem.bangued@gmail.com</div>
                        <button type="button" class="reveal-contact-btn" id="revealContactBtn"><i class="fas fa-paper-plane"></i> Send us a message</button>
                    </div>
                </div>
            </div>

            <div class="copyright">
                <p>&copy; {{ date('Y') }} HappyStem. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <div id="servicePhotosModal" class="service-photos-modal">
        <div class="service-photos-content">
            <div class="service-photos-header">
                <h3 class="service-photos-title" id="servicePhotosTitle">Gallery</h3>
                <span class="service-photos-close">&times;</span>
            </div>
            <div id="servicePhotosGrid" class="service-photos-grid"></div>
        </div>
    </div>

    <div id="contactPopup" class="contact-popup">
        <div class="contact-popup-content">
            <div class="contact-popup-header">
                <h3 class="contact-popup-title">Contact Us</h3>
                <span class="contact-popup-close">&times;</span>
            </div>
            <form class="contact-form" action="{{ route('contact.send') }}" method="POST">
                @csrf
                @auth('web')
                    <input type="hidden" name="name" value="{{ Auth::guard('web')->user()->full_name }}">
                    <input type="hidden" name="email" value="{{ Auth::guard('web')->user()->email }}">
                @else
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" required>
                    </div>
                @endauth
                <div class="form-group">
                    <label>Message</label>
                    <textarea id="popup-message" name="message" rows="5" required></textarea>
                </div>
                <button type="submit" class="submit-btn">Send Message</button>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/main.js') }}?v={{ filemtime(public_path('js/main.js')) }}"></script>
    @stack('scripts')
</body>
</html>
