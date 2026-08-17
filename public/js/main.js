document.addEventListener('DOMContentLoaded', function() {
    // Filter buttons
    const filterBtns = document.querySelectorAll('.filter-btn');
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const category = this.dataset.filter;
            const url = new URL(window.location.href);
            url.searchParams.set('category', category);
            url.searchParams.set('page', '1');
            window.location.href = url.toString();
        });
    });

    // Product card → view product page (whole card is clickable)
    document.querySelectorAll('.product-card[data-href]').forEach(card => {
        card.addEventListener('click', function(e) {
            if (e.target.closest('a, button, input, select, textarea, form')) return;
            window.location.href = card.dataset.href;
        });
    });

    // Service photos modal
    const serviceModal = document.getElementById('servicePhotosModal');
    const serviceClose = document.querySelector('.service-photos-close');
    const serviceGrid = document.getElementById('servicePhotosGrid');
    const serviceTitle = document.getElementById('servicePhotosTitle');

    document.querySelectorAll('.photos-btn').forEach(btn => {
        btn.addEventListener('click', async function() {
            const service = this.dataset.service;
            try {
                const response = await fetch(`/service-photos?service=${service}`);
                const data = await response.json();
                
                serviceTitle.textContent = data.title;
                serviceGrid.innerHTML = '';
                
                data.photos.forEach(photo => {
                    const item = document.createElement('div');
                    item.className = 'service-photo-item';
                    item.innerHTML = `
                        <img src="/images/${photo.image_url}" alt="${photo.caption}" onerror="this.src='https://via.placeholder.com/300x200'">
                        <div class="service-photo-caption">${photo.caption}</div>
                        <button class="service-photo-inquire" data-caption="${photo.caption}">
                            <i class="fas fa-envelope"></i> Inquire
                        </button>
                    `;
                    serviceGrid.appendChild(item);
                });
                
                document.querySelectorAll('.service-photo-inquire').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const caption = this.dataset.caption;
                        const message = `I'm interested in your ${serviceTitle.textContent} services, specifically the "${caption}". Please provide me with more information.`;
                        openContactForm(message);
                    });
                });
                
                serviceModal.style.display = 'block';
            } catch (error) {
                console.error('Error:', error);
            }
        });
    });

    if (serviceClose) {
        serviceClose.addEventListener('click', function() {
            serviceModal.style.display = 'none';
        });
    }

    window.addEventListener('click', function(event) {
        if (event.target === serviceModal) {
            serviceModal.style.display = 'none';
        }
    });

    // Contact popup
    const contactPopup = document.getElementById('contactPopup');
    const openContactBtn = document.getElementById('openContactPopup');
    const closeContactBtn = document.querySelector('.contact-popup-close');

    window.openContactForm = function(prefilledMessage = '') {
        if (prefilledMessage) {
            document.getElementById('popup-message').value = prefilledMessage;
        }
        contactPopup.style.display = 'block';
    }

    if (openContactBtn) {
        openContactBtn.addEventListener('click', () => openContactForm(''));
    }

    if (closeContactBtn) {
        closeContactBtn.addEventListener('click', function() {
            contactPopup.style.display = 'none';
        });
    }

    window.addEventListener('click', function(event) {
        if (event.target === contactPopup) {
            contactPopup.style.display = 'none';
        }
    });

    // Footer "Send us a message" button — open the contact modal
    const revealContactBtn = document.getElementById('revealContactBtn');
    if (revealContactBtn) {
        revealContactBtn.addEventListener('click', function() {
            document.getElementById('popup-message').value = '';
            openContactForm('');
        });
    }

    // Buy Now buttons — add to cart then redirect to checkout
    document.querySelectorAll('.buy-now-btn').forEach(btn => {
        btn.addEventListener('click', async function(e) {
            if (this.tagName === 'A') return;
            e.preventDefault();

            const productId = this.dataset.id;
            const originalText = this.innerHTML;
            this.innerHTML = '⏳ Please wait...';
            this.disabled = true;

            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('quantity', 1);

            try {
                const response = await fetch('/cart/add', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                const data = await response.json();
                if (data.success) {
                    window.location.href = '/checkout';
                } else {
                    alert(data.message);
                    this.innerHTML = originalText;
                    this.disabled = false;
                }
            } catch (error) {
                console.error('Error:', error);
                this.innerHTML = originalText;
                this.disabled = false;
            }
        });
    });

    // Add to cart
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
        btn.addEventListener('click', async function(e) {
            if (this.tagName === 'A') return;
            e.preventDefault();
            
            const productId = this.dataset.id;
            const productName = this.dataset.name;
            
            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('quantity', 1);
            
            try {
                const response = await fetch('/cart/add', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                const data = await response.json();
                
                if (data.success) {
                    alert(`${productName} added to cart!`);
                    updateCartCount();
                } else {
                    alert(data.message);
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });
    });

    // Smooth scrolling
    document.querySelectorAll('nav a[href^="#"], .hero-content a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            if (this.getAttribute('href') === '#') return;
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
});

function updateCartCount() {
    fetch('/cart/count')
        .then(response => response.json())
        .then(data => {
            const cartSpan = document.getElementById('cartCount');
            if (cartSpan && data.count > 0) {
                cartSpan.innerHTML = `(${data.count})`;
            } else if (cartSpan) {
                cartSpan.innerHTML = '';
            }
        });
}
setInterval(updateCartCount, 5000);
updateCartCount();

// ── Customer notification bell ──────────────────────────────────────────
function initCustomerNotifications() {
    const bell = document.getElementById('customerNotifBell');
    if (!bell) return;

    const unreadUrl = bell.dataset.unreadUrl;
    const readUrl = bell.dataset.readUrl;
    const badge = document.getElementById('customerNotifBadge');
    const dropdown = document.getElementById('customerNotifDropdown');
    const list = document.getElementById('customerNotifList');
    const btn = bell.querySelector('.notif-bell-btn');
    const markAll = document.getElementById('customerMarkAll');

    function render(data) {
        badge.textContent = String(data.count || 0);
        badge.classList.toggle('show', data.count > 0);

        list.innerHTML = '';
        if (!data.items || data.items.length === 0) {
            const empty = document.createElement('div');
            empty.className = 'notif-empty';
            empty.textContent = "You're all caught up!";
            list.appendChild(empty);
            return;
        }

        data.items.forEach(item => {
            const link = document.createElement('a');
            link.className = 'notif-item ' + (item.is_read ? 'read' : 'unread');
            link.href = item.link || '#';
            link.setAttribute('data-id', item.id);

            const dot = document.createElement('span');
            dot.className = 'notif-dot';

            const wrap = document.createElement('span');
            wrap.style.flex = '1';

            const title = document.createElement('div');
            title.className = 'notif-item-title';
            title.textContent = item.title || '';

            const body = document.createElement('div');
            body.className = 'notif-item-body';
            body.textContent = item.body || '';

            const time = document.createElement('div');
            time.className = 'notif-item-time';
            time.textContent = item.relative || item.created_at || '';

            wrap.appendChild(title);
            if (body.textContent) wrap.appendChild(body);
            wrap.appendChild(time);

            link.appendChild(dot);
            link.appendChild(wrap);

            link.addEventListener('click', function(e) {
                e.preventDefault();
                fetch(readUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({ notification_id: item.id })
                });
                window.location.href = link.getAttribute('href');
            });

            list.appendChild(link);
        });
    }

    function refresh() {
        fetch(unreadUrl)
            .then(r => r.json())
            .then(render)
            .catch(() => {});
    }

    btn.addEventListener('click', function(e) {
        e.stopPropagation();
        dropdown.classList.toggle('show');
        refresh();
    });

    document.addEventListener('click', function(e) {
        if (!bell.contains(e.target)) {
            dropdown.classList.remove('show');
        }
    });

    markAll.addEventListener('click', function() {
        fetch(readUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams()
        }).then(refresh);
    });

    refresh();
    setInterval(refresh, 20000);
}
initCustomerNotifications();