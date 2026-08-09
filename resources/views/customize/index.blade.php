@extends('layouts.app')

@section('title', 'Customize Your Bouquet | HappyStem')

@section('content')
    <section class="page-heading">
        <h2>Design Your Own Bouquet</h2>
        <p>Pick your flowers, wrapper color and style to create a one-of-a-kind arrangement.</p>
    </section>

    <section style="padding: 20px 0 80px;">
        <div class="container">
            <div style="display:flex;gap:30px;flex-wrap:wrap;align-items:flex-start;">
                <div style="flex:1.3;min-width:320px;">

                    <div style="background:#fff;border-radius:12px;padding:25px;box-shadow:0 8px 25px rgba(0,0,0,0.08);margin-bottom:25px;">
                        <h3 style="color:var(--secondary);margin-bottom:15px;">Choose Your Flowers</h3>
                        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:12px;">
                            @foreach ($flowers as $flower)
                                <div class="flower-card" data-id="{{ $flower->id }}" data-name="{{ $flower->display_name }}"
                                     data-price="{{ $flower->price }}" data-image="{{ $flower->image_url }}"
                                     style="border:2px solid #eee;border-radius:10px;padding:12px;text-align:center;transition:all .2s;">
                                    @if ($flower->image_url)
                                        <img src="{{ asset('images/'.$flower->image_url) }}" alt="{{ $flower->display_name }}"
                                             style="width:70px;height:70px;object-fit:cover;border-radius:50%;margin-bottom:6px;">
                                    @else
                                        <i class="fas fa-seedling" style="font-size:1.8rem;color:var(--primary);margin-bottom:6px;display:block;"></i>
                                    @endif
                                    <div style="font-size:0.9rem;color:var(--dark);font-weight:600;">{{ $flower->display_name }}</div>
                                    <div style="font-size:0.82rem;color:var(--accent);margin-bottom:8px;">₱{{ number_format($flower->price, 2) }}/stem</div>
                                    <div style="display:flex;align-items:center;justify-content:center;gap:10px;">
                                        <button type="button" class="qty-minus" style="background:var(--primary);color:#fff;border:none;width:26px;height:26px;border-radius:50%;cursor:pointer;font-weight:bold;">−</button>
                                        <span class="qty-value" style="font-weight:bold;min-width:22px;text-align:center;">0</span>
                                        <button type="button" class="qty-plus" style="background:var(--primary);color:#fff;border:none;width:26px;height:26px;border-radius:50%;cursor:pointer;font-weight:bold;">+</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div style="background:#fff;border-radius:12px;padding:25px;box-shadow:0 8px 25px rgba(0,0,0,0.08);margin-bottom:25px;">
                        <h3 style="color:var(--secondary);margin-bottom:15px;">Wrapper Color</h3>
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

                    <div style="background:#fff;border-radius:12px;padding:25px;box-shadow:0 8px 25px rgba(0,0,0,0.08);margin-bottom:25px;">
                        <h3 style="color:var(--secondary);margin-bottom:15px;">Arrangement Style</h3>
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

                <div style="flex:1;min-width:300px;">
                    <div style="background:#fff;border-radius:12px;padding:25px;box-shadow:0 8px 25px rgba(0,0,0,0.08);position:sticky;top:90px;">
                        <h3 style="color:var(--secondary);margin-bottom:15px;">Your Design</h3>

                        <div style="width:100%;height:220px;border-radius:10px;overflow:hidden;background:var(--light);display:flex;align-items:center;justify-content:center;margin-bottom:15px;">
                            <img id="designPreview" src="{{ asset('images/rs.jpg') }}" alt="Design preview" style="width:100%;height:100%;object-fit:cover;">
                        </div>

                        <div id="designSummary" style="font-size:0.9rem;color:var(--dark);margin-bottom:15px;">
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
        const data = { id: card.dataset.id, name: card.dataset.name, price: parseFloat(card.dataset.price || 0), image: card.dataset.image || '', qty: 0 };
        flowers[data.id] = data;
        const val = card.querySelector('.qty-value');
        card.querySelector('.qty-plus').addEventListener('click', (e) => { e.stopPropagation(); setQty(card, data, data.qty + 1); });
        card.querySelector('.qty-minus').addEventListener('click', (e) => { e.stopPropagation(); setQty(card, data, data.qty - 1); });
    });
    function setQty(card, data, qty) {
        data.qty = Math.max(0, qty);
        card.querySelector('.qty-value').textContent = data.qty;
        card.style.borderColor = data.qty > 0 ? 'var(--accent)' : '#eee';
        card.style.background = data.qty > 0 ? 'rgba(209,123,136,0.06)' : '#fff';
        recompute();
    }

    let color = null;
    document.querySelectorAll('.color-card').forEach(el => {
        el.addEventListener('click', () => {
            document.querySelectorAll('.color-card').forEach(x => x.style.borderColor = 'transparent');
            el.style.borderColor = 'var(--dark)';
            color = { name: el.dataset.name, price: parseFloat(el.dataset.price || 0) };
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

    function selectedFlowers() {
        return Object.values(flowers).filter(f => f.qty > 0);
    }

    function recompute() {
        const sel = selectedFlowers();
        let total = 0;
        const lines = [];
        sel.forEach(f => {
            const sub = f.price * f.qty;
            total += sub;
            lines.push(`<p><strong>${f.name}</strong> &times; ${f.qty} &mdash; &#8369;${sub.toFixed(2)}</p>`);
        });
        if (color) {
            total += color.price;
            lines.push(`<p>Wrapper: ${color.name}${color.price > 0 ? ' (+&#8369;' + color.price.toFixed(2) + ')' : ''}</p>`);
        }
        if (style) {
            total += style.price;
            lines.push(`<p>Style: ${style.name} (+&#8369;${style.price.toFixed(2)})</p>`);
        }
        document.getElementById('designSummary').innerHTML = lines.length ? lines.join('') : '<p style="color:var(--secondary);">No selections yet.</p>';
        document.getElementById('designTotal').textContent = '₱' + total.toFixed(2);

        const first = sel[0];
        const previewImg = document.getElementById('designPreview');
        if (first && first.image) {
            previewImg.src = "{{ asset('images/') }}/" + first.image;
        } else if (style && style.image) {
            previewImg.src = "{{ asset('images/') }}/" + style.image;
        }
    }

    function summaryText() {
        const sel = selectedFlowers();
        const parts = [];
        sel.forEach(f => parts.push(`${f.name} x${f.qty}`));
        if (color) parts.push('Wrapper: ' + color.name);
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
