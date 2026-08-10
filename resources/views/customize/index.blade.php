@extends('layouts.app')

@section('title', 'Customize Your Bouquet | HappyStem')

@section('content')
    <section class="page-heading">
        <h2>Design Your Own Bouquet</h2>
        <p>Pick your flowers, filler, wrapper color and style to create a one-of-a-kind arrangement.</p>
    </section>

    <style>
        .customize-section { background:#fff;border-radius:12px;box-shadow:0 8px 25px rgba(0,0,0,0.08);margin-bottom:25px;overflow:hidden; }
        .section-toggle { width:100%;text-align:left;padding:18px 22px;background:none;border:none;font-size:1.05rem;font-weight:700;color:var(--secondary);cursor:pointer;display:flex;justify-content:space-between;align-items:center; }
        .section-toggle::after { content:'▾';transition:transform .2s;color:var(--accent); }
        .section-toggle.open::after { transform:rotate(180deg); }
        .section-body { display:none;padding:0 22px 22px; }
        .section-body.open { display:block; }
        .req { font-size:0.68rem;color:#fff;background:#d17b88;border-radius:20px;padding:2px 9px;font-weight:600;margin-left:8px;vertical-align:middle; }
        .flower-grid { display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:12px; }
        .flower-card { border:2px solid #eee;border-radius:10px;padding:12px;text-align:center;transition:all .2s; }
        .flower-card.selected { border-color:var(--accent);background:rgba(209,123,136,0.06); }
        .flower-price { font-size:0.82rem;color:var(--accent);margin-bottom:8px; }
        .variant-toggle { margin-top:8px;background:#f9f3f4;border:1px solid #e8b4bc;color:#d17b88;border-radius:20px;padding:4px 12px;font-size:0.75rem;font-weight:600;cursor:pointer; }
        .variant-toggle:hover { background:#fdf2f4; }
        .variant-panel { display:none;margin-top:10px;padding-top:10px;border-top:1px dashed #eee; }
        .variant-panel.open { display:block; }
        .v-group { margin-bottom:9px; }
        .v-label { font-size:0.7rem;color:#8a8a8a;text-transform:uppercase;letter-spacing:.5px;margin-bottom:5px; }
        .v-row { display:flex;gap:6px;flex-wrap:wrap;justify-content:center; }
        .size-option { border:1.5px solid #ddd;background:#fff;border-radius:20px;padding:4px 10px;font-size:0.75rem;cursor:pointer;color:var(--dark); }
        .size-option.selected { border-color:var(--accent);background:#fdf2f4;color:#d17b88;font-weight:600; }
        .color-option { width:30px;height:30px;border-radius:50%;border:2px solid #fff;box-shadow:0 0 0 1.5px #ddd;cursor:pointer;display:inline-block; }
        .color-option.selected { box-shadow:0 0 0 2.5px var(--accent);transform:scale(1.18); }
        .filler-card { border:2px solid #eee;border-radius:10px;padding:12px;text-align:center;transition:all .2s;cursor:pointer; }
        .filler-card.selected { border-color:var(--accent);background:rgba(209,123,136,0.06); }
        .picked-thumbs { display:flex;gap:10px;flex-wrap:wrap;margin-bottom:15px; }
        .thumb-box { width:56px;height:56px;border-radius:10px;overflow:hidden;border:1px solid #eee;position:relative;background:var(--light);display:flex;align-items:center;justify-content:center; }
        .thumb-box img { width:100%;height:100%;object-fit:cover; }
        .thumb-box i { font-size:1.2rem;color:var(--primary); }
        .thumb-qty { position:absolute;bottom:-4px;right:-4px;background:var(--accent);color:#fff;font-size:0.65rem;font-weight:700;border-radius:10px;padding:1px 6px; }
        .thumb-swatch { width:100%;height:100%;display:block; }
        .design-summary .var { color:var(--accent);font-size:0.85rem; }
    </style>

    <section style="padding: 20px 0 80px;">
        <div class="container">
            <div style="display:flex;gap:30px;flex-wrap:wrap;align-items:flex-start;">
                <div style="flex:1.3;min-width:320px;">

                    <div class="customize-section">
                        <button type="button" class="section-toggle open">Choose Your Flowers</button>
                        <div class="section-body open">
                            <div class="flower-grid">
                                @foreach ($flowers as $flower)
                                    @php
                                        $sizeVariants = $flower->variants->where('variant_type', 'size');
                                        $colorVariants = $flower->variants->where('variant_type', 'color');
                                    @endphp
                                    <div class="flower-card" data-id="{{ $flower->id }}" data-name="{{ $flower->display_name }}"
                                         data-base-price="{{ $flower->price }}" data-image="{{ $flower->image_url }}">
                                        @if ($flower->image_url)
                                            <img src="{{ asset('images/'.$flower->image_url) }}" alt="{{ $flower->display_name }}"
                                                 style="width:70px;height:70px;object-fit:cover;border-radius:50%;margin-bottom:6px;">
                                        @else
                                            <i class="fas fa-seedling" style="font-size:1.8rem;color:var(--primary);margin-bottom:6px;display:block;"></i>
                                        @endif
                                        <div style="font-size:0.9rem;color:var(--dark);font-weight:600;">{{ $flower->display_name }}</div>
                                        <div class="flower-price" id="flower-price-{{ $flower->id }}">₱{{ number_format($flower->price, 2) }}/stem</div>
                                        <div style="display:flex;align-items:center;justify-content:center;gap:10px;">
                                            <button type="button" class="qty-minus" style="background:var(--primary);color:#fff;border:none;width:26px;height:26px;border-radius:50%;cursor:pointer;font-weight:bold;">−</button>
                                            <span class="qty-value" style="font-weight:bold;min-width:22px;text-align:center;">0</span>
                                            <button type="button" class="qty-plus" style="background:var(--primary);color:#fff;border:none;width:26px;height:26px;border-radius:50%;cursor:pointer;font-weight:bold;">+</button>
                                        </div>
                                        @if ($sizeVariants->count() || $colorVariants->count())
                                            <button type="button" class="variant-toggle">Options ▾</button>
                                            <div class="variant-panel">
                                                @if ($sizeVariants->count())
                                                    <div class="v-group">
                                                        <div class="v-label">Size</div>
                                                        <div class="v-row">
                                                            @foreach ($sizeVariants as $variant)
                                                                <button type="button" class="size-option" data-name="{{ $variant->display_name }}" data-price="{{ $variant->price }}">
                                                                    {{ $variant->display_name }}@if ($variant->price > 0) <span style="color:var(--accent);font-weight:700;">₱{{ number_format($variant->price, 2) }}</span>@endif
                                                                </button>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                                @if ($colorVariants->count())
                                                    <div class="v-group">
                                                        <div class="v-label">Color</div>
                                                        <div class="v-row">
                                                            @foreach ($colorVariants as $variant)
                                                                @php
                                                                    $twoTone = stripos($variant->display_name, 'two') !== false;
                                                                    $bg = $variant->hex_color
                                                                        ?: ($twoTone
                                                                            ? (stripos($variant->display_name, 'violet') !== false
                                                                                ? 'linear-gradient(135deg,#9b59b6,#e8b4bc)'
                                                                                : 'linear-gradient(135deg,#ffffff,#e8b4bc)')
                                                                            : 'linear-gradient(45deg,#e74c3c,#e8b4bc,#f1c40f,#9b59b6)');
                                                                @endphp
                                                                <span class="color-option" data-name="{{ $variant->display_name }}" data-price="{{ $variant->price }}"
                                                                      title="{{ $variant->display_name }}" style="background:{{ $bg }};"></span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="customize-section">
                        <button type="button" class="section-toggle">Fillers</button>
                        <div class="section-body">
                            <div class="flower-grid">
                                @foreach ($fillers as $filler)
                                    <div class="filler-card" data-id="{{ $filler->id }}" data-name="{{ $filler->display_name }}"
                                         data-price="{{ $filler->price }}" data-image="{{ $filler->image_url }}">
                                        @if ($filler->image_url)
                                            <img src="{{ asset('images/'.$filler->image_url) }}" alt="{{ $filler->display_name }}"
                                                 style="width:70px;height:70px;object-fit:cover;border-radius:50%;margin-bottom:6px;">
                                        @else
                                            <i class="fas fa-leaf" style="font-size:1.8rem;color:var(--secondary);margin-bottom:6px;display:block;"></i>
                                        @endif
                                        <div style="font-size:0.9rem;color:var(--dark);font-weight:600;">{{ $filler->display_name }}</div>
                                        <div class="flower-price" id="filler-price-{{ $filler->id }}">
                                            @if ($filler->price > 0)
                                                ₱{{ number_format($filler->price, 2) }}/stem
                                            @else
                                                <span style="color:#8a8a8a;">Price coming soon</span>
                                            @endif
                                        </div>
                                        <div class="filler-tick" style="font-size:0.72rem;color:var(--secondary);font-weight:600;display:none;">✓ Selected</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="customize-section">
                        <button type="button" class="section-toggle">Wrapper Color</button>
                        <div class="section-body">
                            <div style="display:flex;gap:14px;flex-wrap:wrap;">
                                @php
                                    $swatches = [
                                        'red' => '#e74c3c', 'pink' => '#e8b4bc', 'white' => '#f9f3f4',
                                        'yellow' => '#f1c40f', 'purple' => '#9b59b6',
                                        'mixed' => 'linear-gradient(45deg,#e74c3c,#e8b4bc,#f1c40f,#9b59b6)',
                                    ];
                                    $swatchDefault = 'linear-gradient(45deg,#e74c3c,#e8b4bc,#f1c40f,#9b59b6)';
                                @endphp
                                @foreach ($colors as $color)
                                    <div class="color-card" data-name="{{ $color->display_name }}" data-price="{{ $color->price }}"
                                         title="{{ $color->display_name }}"
                                         style="border:3px solid transparent;border-radius:50%;cursor:pointer;text-align:center;transition:all .2s;">
                                        <div style="width:56px;height:56px;border-radius:50%;background:{{ $color->hex_color ?: ($swatches[$color->name] ?? $swatchDefault) }};border:2px solid #ddd;box-shadow:0 2px 8px rgba(0,0,0,0.12);"></div>
                                        <div style="font-size:0.75rem;color:var(--dark);margin-top:4px;">{{ $color->display_name }}</div>
                                        @if ($color->price > 0)
                                            <div style="font-size:0.7rem;color:var(--accent);">+₱{{ number_format($color->price, 2) }}</div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="customize-section">
                        <button type="button" class="section-toggle">Arrangement Style</button>
                        <div class="section-body">
                            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:12px;">
                                @foreach ($styles as $style)
                                    <div class="style-card" data-id="{{ $style->id }}" data-name="{{ $style->display_name }}"
                                         data-price="{{ $style->price }}" data-image="{{ $style->image_url }}"
                                         style="border:2px solid #eee;border-radius:10px;padding:12px;cursor:pointer;text-align:center;transition:all .2s;">
                                        @if ($style->image_url)
                                            <img src="{{ asset('images/'.$style->image_url) }}" alt="{{ $style->display_name }}"
                                                 style="width:70px;height:70px;object-fit:cover;border-radius:50%;margin-bottom:6px;" onerror="this.style.display='none';">
                                        @endif
                                        <div style="font-size:0.9rem;color:var(--dark);font-weight:600;">{{ $style->display_name }}</div>
                                        <div style="font-size:0.82rem;color:var(--accent);">+₱{{ number_format($style->price, 2) }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div style="flex:1;min-width:300px;">
                    <div style="background:#fff;border-radius:12px;padding:25px;box-shadow:0 8px 25px rgba(0,0,0,0.08);position:sticky;top:90px;">
                        <h3 style="color:var(--secondary);margin-bottom:15px;">Your Design</h3>

                        <div class="picked-thumbs" id="pickedThumbs">
                            <p style="color:var(--secondary);font-size:0.88rem;">Nothing picked yet.</p>
                        </div>

                        <div class="design-summary" id="designSummary" style="font-size:0.9rem;color:var(--dark);margin-bottom:15px;">
                            <p style="color:var(--secondary);">No selections yet.</p>
                        </div>

                        <div class="product-price" id="designTotal" style="font-size:1.5rem;text-align:center;margin-bottom:15px;">₱0.00</div>

                        <button type="button" class="btn" id="addCustomToCart" style="width:100%;text-align:center;margin-bottom:10px;">
                            <i class="fas fa-cart-plus"></i> Add to Cart
                        </button>
                        <button type="button" class="btnn" id="buyNowBtn" style="width:100%;text-align:center;">
                            <i class="fas fa-bolt"></i> Buy Now
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    const flowers = {};
    document.querySelectorAll('.flower-card').forEach(card => {
        const data = {
            id: card.dataset.id, name: card.dataset.name, basePrice: parseFloat(card.dataset.basePrice || 0),
            image: card.dataset.image || '', qty: 0, size: null, color: null
        };
        flowers[data.id] = data;
        const val = card.querySelector('.qty-value');
        card.querySelector('.qty-plus').addEventListener('click', (e) => { e.stopPropagation(); setQty(card, data, data.qty + 1); });
        card.querySelector('.qty-minus').addEventListener('click', (e) => { e.stopPropagation(); setQty(card, data, data.qty - 1); });

        const toggle = card.querySelector('.variant-toggle');
        if (toggle) {
            toggle.addEventListener('click', (e) => {
                e.stopPropagation();
                const panel = card.querySelector('.variant-panel');
                const open = panel.classList.toggle('open');
                toggle.textContent = open ? 'Options ▴' : 'Options ▾';
            });
        }

        card.querySelectorAll('.size-option').forEach(opt => {
            opt.addEventListener('click', () => {
                card.querySelectorAll('.size-option').forEach(x => x.classList.remove('selected'));
                opt.classList.add('selected');
                data.size = { name: opt.dataset.name, price: parseFloat(opt.dataset.price || 0) };
                updateFlowerPrice(card, data);
                recompute();
            });
        });

        card.querySelectorAll('.color-option').forEach(opt => {
            opt.addEventListener('click', () => {
                card.querySelectorAll('.color-option').forEach(x => x.classList.remove('selected'));
                opt.classList.add('selected');
                data.color = { name: opt.dataset.name, price: parseFloat(opt.dataset.price || 0) };
                recompute();
            });
        });
    });

    function setQty(card, data, qty) {
        data.qty = Math.max(0, qty);
        card.querySelector('.qty-value').textContent = data.qty;
        card.classList.toggle('selected', data.qty > 0);
        recompute();
    }

    function unitPrice(f) {
        let p = f.basePrice;
        if (f.size && parseFloat(f.size.price) > 0) p = parseFloat(f.size.price);
        if (f.color && parseFloat(f.color.price) > 0) p = parseFloat(f.color.price);
        return p;
    }

    function updateFlowerPrice(card, data) {
        const el = document.getElementById('flower-price-' + data.id);
        if (el) el.textContent = '₱' + unitPrice(data).toFixed(2) + '/stem';
    }

    const fillers = {};
    document.querySelectorAll('.filler-card').forEach(card => {
        const data = {
            id: card.dataset.id, name: card.dataset.name, price: parseFloat(card.dataset.price || 0),
            image: card.dataset.image || '', qty: 0
        };
        fillers[data.id] = data;
        card.addEventListener('click', () => {
            data.qty = data.qty ? 0 : 1;
            card.classList.toggle('selected', data.qty > 0);
            const tick = card.querySelector('.filler-tick');
            if (tick) tick.style.display = data.qty > 0 ? '' : 'none';
            recompute();
        });
    });

    let wrapper = null;
    document.querySelectorAll('.color-card').forEach(el => {
        el.addEventListener('click', () => {
            document.querySelectorAll('.color-card').forEach(x => x.style.borderColor = 'transparent');
            el.style.borderColor = 'var(--dark)';
            wrapper = { name: el.dataset.name, price: parseFloat(el.dataset.price || 0), hex: el.querySelector('div').style.background };
            recompute();
        });
    });

    let style = null;
    document.querySelectorAll('.style-card').forEach(el => {
        el.addEventListener('click', () => {
            document.querySelectorAll('.style-card').forEach(x => x.style.borderColor = '#eee');
            el.style.borderColor = 'var(--accent)';
            style = { name: el.dataset.name, price: parseFloat(el.dataset.price || 0), image: el.dataset.image || '' };
            recompute();
        });
    });

    document.querySelectorAll('.section-toggle').forEach(btn => {
        btn.addEventListener('click', () => {
            const body = btn.nextElementSibling;
            const wasOpen = body.classList.contains('open');
            document.querySelectorAll('.section-toggle').forEach(b => {
                b.classList.remove('open');
                b.nextElementSibling.classList.remove('open');
            });
            if (!wasOpen) {
                btn.classList.add('open');
                body.classList.add('open');
            }
        });
    });

    function selectedFlowers() {
        return Object.values(flowers).filter(f => f.qty > 0);
    }
    function selectedFillers() {
        return Object.values(fillers).filter(f => f.qty > 0);
    }

    function variantLabel(f) {
        return [f.size?.name, f.color?.name].filter(Boolean).join(', ');
    }

    function thumbBox(image, title, badge) {
        const img = image
            ? '<img src="{{ asset('images/') }}/' + image + '" alt="">'
            : '<i class="fas fa-seedling"></i>';
        return '<div class="thumb-box" title="' + title + '">' + img + (badge ? '<span class="thumb-qty">×' + badge + '</span>' : '') + '</div>';
    }

    function swatchBox(name, hex) {
        const bg = hex || 'linear-gradient(45deg,#e74c3c,#e8b4bc,#f1c40f,#9b59b6)';
        return '<div class="thumb-box" title="Wrapper: ' + name + '"><span class="thumb-swatch" style="background:' + bg + '"></span></div>';
    }

    function renderThumbs(sel, selF) {
        const box = document.getElementById('pickedThumbs');
        if (!sel.length && !selF.length && !wrapper && !style) {
            box.innerHTML = '<p style="color:var(--secondary);font-size:0.88rem;">Nothing picked yet.</p>';
            return;
        }
        let html = '';
        sel.forEach(f => {
            html += thumbBox(f.image, f.name + (variantLabel(f) ? ' (' + variantLabel(f) + ')' : ''), f.qty > 1 ? f.qty : '');
        });
        selF.forEach(f => html += thumbBox(f.image, f.name, f.qty > 1 ? f.qty : ''));
        if (wrapper) html += swatchBox(wrapper.name, wrapper.hex);
        if (style) html += thumbBox(style.image, 'Style: ' + style.name, '');
        box.innerHTML = html;
    }

    function recompute() {
        const sel = selectedFlowers();
        const selF = selectedFillers();
        let total = 0;
        const lines = [];
        sel.forEach(f => {
            const sub = unitPrice(f) * f.qty;
            total += sub;
            const vars = variantLabel(f);
            lines.push('<p><strong>' + f.name + '</strong>' + (vars ? ' <span class="var">(' + vars + ')</span>' : '') + ' &times; ' + f.qty + ' &mdash; &#8369;' + sub.toFixed(2) + '</p>');
        });
        selF.forEach(f => {
            if (f.price <= 0) return;
            const sub = f.price * f.qty;
            total += sub;
            lines.push('<p><strong>' + f.name + '</strong> &times; ' + f.qty + ' &mdash; &#8369;' + sub.toFixed(2) + '</p>');
        });
        if (wrapper) {
            total += wrapper.price;
            lines.push('<p>Wrapper: <span class="var">' + wrapper.name + '</span>' + (wrapper.price > 0 ? ' (+&#8369;' + wrapper.price.toFixed(2) + ')' : '') + '</p>');
        }
        if (style) {
            total += style.price;
            lines.push('<p>Style: <span class="var">' + style.name + '</span> (+&#8369;' + style.price.toFixed(2) + ')</p>');
        }
        document.getElementById('designSummary').innerHTML = lines.length ? lines.join('') : '<p style="color:var(--secondary);">No selections yet.</p>';
        document.getElementById('designTotal').textContent = '₱' + total.toFixed(2);
        renderThumbs(sel, selF);
    }

    function summaryText() {
        const parts = [];
        selectedFlowers().forEach(f => {
            parts.push(f.name + (variantLabel(f) ? ' (' + variantLabel(f) + ')' : '') + ' x' + f.qty);
        });
        selectedFillers().forEach(f => parts.push(f.name + ' x' + f.qty));
        if (wrapper) parts.push('Wrapper: ' + wrapper.name);
        if (style) parts.push('Style: ' + style.name);
        return parts.join('; ');
    }

    function currentTotal() {
        return parseFloat(document.getElementById('designTotal').textContent.replace('₱', '')) || 0;
    }

    async function addCustom() {
        if (selectedFlowers().length === 0) {
            alert('Please select at least one flower first.');
            return null;
        }
        if (!wrapper) {
            alert('Please choose a wrapper color before adding to cart.');
            return null;
        }
        const total = currentTotal();
        const fd = new FormData();
        fd.append('name', 'Custom Flower Arrangement');
        fd.append('price', total);
        fd.append('description', summaryText());
        fd.append('quantity', 1);
        const res = await fetch("{{ route('cart.addCustom') }}", {
            method: 'POST',
            body: fd,
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
        });
        const data = await res.json();
        if (data.success) {
            if (window.updateCartCount) updateCartCount();
            return data;
        }
        if (data.login_required) {
            window.location.href = "{{ route('login', ['redirect' => request()->url()]) }}";
        } else {
            alert(data.message);
        }
        return null;
    }

    document.getElementById('addCustomToCart').addEventListener('click', async () => {
        const data = await addCustom();
        if (data && data.success) alert('Custom design added to cart!');
    });

    document.getElementById('buyNowBtn').addEventListener('click', async () => {
        const clearRes = await fetch("{{ route('cart.clear') }}", {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
        });
        await clearRes.json();
        const data = await addCustom();
        if (data && data.success) window.location.href = "{{ route('checkout.index') }}";
    });
</script>
@endpush
