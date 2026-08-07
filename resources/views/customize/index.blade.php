@extends('layouts.app')

@section('title', 'Customize Your Bouquet | HappyStem')

@section('content')
    <section class="page-heading">
        <h2>Design Your Own Bouquet</h2>
        <p>Pick your flowers, colors, style and add-ons to create a one-of-a-kind arrangement.</p>
    </section>

    <section style="padding: 20px 0 80px;">
        <div class="container">
            <div style="display:flex;gap:30px;flex-wrap:wrap;align-items:flex-start;">
                <div style="flex:1.3;min-width:320px;">
                    <div style="background:#fff;border-radius:12px;padding:25px;box-shadow:0 8px 25px rgba(0,0,0,0.08);margin-bottom:25px;">
                        <h3 style="color:var(--secondary);margin-bottom:15px;">Start From a Preset</h3>
                        <div class="catalogue" style="grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:15px;">
                            @foreach ($presets as $preset)
                                <div class="product-card preset-apply" data-name="{{ $preset->name }}" data-price="{{ $preset->base_price }}"
                                     data-image="{{ $preset->image_url }}" data-description="{{ $preset->description }}" style="cursor:pointer;">
                                    <div class="product-img" style="height:120px;">
                                        <img src="{{ asset('images/'.$preset->image_url) }}" alt="{{ $preset->name }}">
                                    </div>
                                    <div class="product-info" style="padding:12px;">
                                        <h3 style="font-size:0.95rem;">{{ $preset->name }}</h3>
                                        <div class="product-price" style="font-size:0.9rem;">₱{{ number_format($preset->base_price, 2) }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div style="background:#fff;border-radius:12px;padding:25px;box-shadow:0 8px 25px rgba(0,0,0,0.08);margin-bottom:25px;">
                        <h3 style="color:var(--secondary);margin-bottom:15px;">Choose Your Flowers</h3>
                        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:12px;">
                            @foreach ($flowers as $flower)
                                <label class="opt-card flower-opt" data-name="{{ $flower->display_name }}" data-price="{{ $flower->price }}" data-image="{{ $flower->image_url }}"
                                       style="border:2px solid #eee;border-radius:10px;padding:10px;cursor:pointer;text-align:center;">
                                    <input type="radio" name="flower" value="{{ $flower->name }}" style="display:none;">
                                    @if ($flower->image_url)
                                        <img src="{{ asset('images/'.$flower->image_url) }}" alt="{{ $flower->display_name }}" style="width:60px;height:60px;object-fit:cover;border-radius:50%;margin-bottom:6px;">
                                    @else
                                        <i class="fas fa-seedling" style="font-size:1.6rem;color:var(--primary);margin-bottom:6px;display:block;"></i>
                                    @endif
                                    <div style="font-size:0.88rem;color:var(--dark);">{{ $flower->display_name }}</div>
                                    <div style="font-size:0.8rem;color:var(--accent);font-weight:600;">+₱{{ number_format($flower->price, 2) }}</div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div style="background:#fff;border-radius:12px;padding:25px;box-shadow:0 8px 25px rgba(0,0,0,0.08);margin-bottom:25px;">
                        <h3 style="color:var(--secondary);margin-bottom:15px;">Color</h3>
                        <div style="display:flex;gap:12px;flex-wrap:wrap;">
                            @foreach ($colors as $color)
                                <label class="opt-card color-opt" data-name="{{ $color->display_name }}" data-price="{{ $color->price }}"
                                       style="border:2px solid #eee;border-radius:10px;padding:10px 14px;cursor:pointer;text-align:center;">
                                    <input type="radio" name="color" value="{{ $color->name }}" style="display:none;">
                                    <div style="font-size:0.88rem;color:var(--dark);">{{ $color->display_name }}</div>
                                    @if ($color->price > 0)
                                        <div style="font-size:0.78rem;color:var(--accent);">+₱{{ number_format($color->price, 2) }}</div>
                                    @endif
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div style="background:#fff;border-radius:12px;padding:25px;box-shadow:0 8px 25px rgba(0,0,0,0.08);margin-bottom:25px;">
                        <h3 style="color:var(--secondary);margin-bottom:15px;">Style</h3>
                        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:12px;">
                            @foreach ($styles as $style)
                                <label class="opt-card style-opt" data-name="{{ $style->display_name }}" data-price="{{ $style->price }}" data-image="{{ $style->image_url }}"
                                       style="border:2px solid #eee;border-radius:10px;padding:10px;cursor:pointer;text-align:center;">
                                    <input type="radio" name="style" value="{{ $style->name }}" style="display:none;">
                                    @if ($style->image_url)
                                        <img src="{{ asset('images/'.$style->image_url) }}" alt="{{ $style->display_name }}" style="width:60px;height:60px;object-fit:cover;border-radius:50%;margin-bottom:6px;" onerror="this.style.display='none';">
                                    @endif
                                    <div style="font-size:0.88rem;color:var(--dark);">{{ $style->display_name }}</div>
                                    <div style="font-size:0.8rem;color:var(--accent);font-weight:600;">+₱{{ number_format($style->price, 2) }}</div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div style="background:#fff;border-radius:12px;padding:25px;box-shadow:0 8px 25px rgba(0,0,0,0.08);margin-bottom:25px;">
                        <h3 style="color:var(--secondary);margin-bottom:15px;">Add-Ons</h3>
                        <div style="display:flex;gap:12px;flex-wrap:wrap;">
                            @foreach ($addons as $addon)
                                <label class="opt-card addon-opt" data-name="{{ $addon->display_name }}" data-price="{{ $addon->price }}"
                                       style="border:2px solid #eee;border-radius:10px;padding:10px 14px;cursor:pointer;text-align:center;">
                                    <input type="checkbox" name="addon" value="{{ $addon->name }}" style="display:none;">
                                    <div style="font-size:0.88rem;color:var(--dark);">{{ $addon->display_name }}</div>
                                    <div style="font-size:0.78rem;color:var(--accent);">+₱{{ number_format($addon->price, 2) }}</div>
                                </label>
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

                        <div class="form-group" style="margin-bottom:15px;">
                            <label>Quantity</label>
                            <input type="number" id="customQty" value="1" min="1" style="width:100%;">
                        </div>

                        <button type="button" class="btn" id="addCustomToCart" style="width:100%;text-align:center;margin-bottom:10px;">
                            <i class="fas fa-cart-plus"></i> Add to Cart
                        </button>
                        <button type="button" class="btnn" id="saveDesign" style="width:100%;text-align:center;">
                            <i class="fas fa-save"></i> Save Design
                        </button>
                    </div>

                    @auth('web')
                        @if ($savedDesigns->isNotEmpty())
                            <div style="background:#fff;border-radius:12px;padding:25px;box-shadow:0 8px 25px rgba(0,0,0,0.08);margin-top:25px;">
                                <h3 style="color:var(--secondary);margin-bottom:15px;">My Saved Designs</h3>
                                @foreach ($savedDesigns as $design)
                                    <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid #f0f0f0;">
                                        <div>
                                            <strong style="color:var(--dark);">{{ $design->design_name ?: 'Untitled' }}</strong>
                                            <p style="font-size:0.85rem;color:var(--accent);">₱{{ number_format($design->total_price, 2) }}</p>
                                        </div>
                                        <div style="display:flex;gap:8px;">
                                            <button type="button" class="load-design-btn submit-btn" style="padding:6px 12px;font-size:0.8rem;"
                                                    data-json="{{ $design->design_data ?: '{}' }}" data-name="{{ $design->design_name ?: 'Custom Design' }}">
                                                Load
                                            </button>
                                            <form action="{{ route('customize.delete') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $design->id }}">
                                                <button type="submit" class="submit-btn" style="padding:6px 12px;font-size:0.8rem;background:#c94a4a;">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    const selection = { flower: null, color: null, style: null, addons: [] };

    function selectedPrice(type) {
        const opt = selection[type];
        if (!opt) return 0;
        const el = document.querySelector(`.${type}-opt[data-name="${opt}"]`);
        return el ? parseFloat(el.dataset.price || 0) : 0;
    }

    function recompute() {
        let total = 0;
        const lines = [];
        if (selection.flower) {
            total += selectedPrice('flower');
            lines.push(`<p><strong>${selection.flower}</strong> (Flowers)</p>`);
        }
        if (selection.color) {
            total += selectedPrice('color');
            lines.push(`<p>${selection.color} (Color)</p>`);
        }
        if (selection.style) {
            total += selectedPrice('style');
            lines.push(`<p>${selection.style} (Style)</p>`);
        }
        if (selection.addons.length) {
            selection.addons.forEach(name => {
                const el = document.querySelector(`.addon-opt[data-name="${name}"]`);
                if (el) total += parseFloat(el.dataset.price || 0);
            });
            lines.push(`<p>${selection.addons.join(', ')} (Add-ons)</p>`);
        }
        document.getElementById('designSummary').innerHTML = lines.length ? lines.join('') : '<p style="color:var(--secondary);">No selections yet.</p>';
        document.getElementById('designTotal').textContent = '₱' + total.toFixed(2);

        if (selection.flower) {
            const el = document.querySelector(`.flower-opt[data-name="${selection.flower}"]`);
            const img = el ? el.dataset.image : null;
            if (img) {
                document.getElementById('designPreview').src = "{{ asset('images/') }}/" + img;
            }
        }
    }

    document.querySelectorAll('.flower-opt').forEach(el => {
        el.addEventListener('click', () => {
            document.querySelectorAll('.flower-opt').forEach(x => x.style.borderColor = '#eee');
            el.style.borderColor = 'var(--accent)';
            selection.flower = el.dataset.name;
            recompute();
        });
    });

    document.querySelectorAll('.color-opt').forEach(el => {
        el.addEventListener('click', () => {
            document.querySelectorAll('.color-opt').forEach(x => x.style.borderColor = '#eee');
            el.style.borderColor = 'var(--accent)';
            selection.color = el.dataset.name;
            recompute();
        });
    });

    document.querySelectorAll('.style-opt').forEach(el => {
        el.addEventListener('click', () => {
            document.querySelectorAll('.style-opt').forEach(x => x.style.borderColor = '#eee');
            el.style.borderColor = 'var(--accent)';
            selection.style = el.dataset.name;
            recompute();
        });
    });

    document.querySelectorAll('.addon-opt').forEach(el => {
        el.addEventListener('click', () => {
            const checked = el.style.borderColor === 'rgb(209, 123, 136)' || el.style.borderColor === 'var(--accent)';
            el.style.borderColor = checked ? '#eee' : 'var(--accent)';
            const name = el.dataset.name;
            if (checked) {
                selection.addons = selection.addons.filter(n => n !== name);
            } else {
                selection.addons.push(name);
            }
            recompute();
        });
    });

    document.querySelectorAll('.preset-apply').forEach(el => {
        el.addEventListener('click', () => {
            document.querySelectorAll('.flower-opt').forEach(x => x.style.borderColor = '#eee');
            document.querySelectorAll('.color-opt').forEach(x => x.style.borderColor = '#eee');
            document.querySelectorAll('.style-opt').forEach(x => x.style.borderColor = '#eee');
            document.querySelectorAll('.addon-opt').forEach(x => x.style.borderColor = '#eee');
            selection.flower = null;
            selection.color = null;
            selection.style = null;
            selection.addons = [];
            selection.flower = el.dataset.name;
            document.getElementById('designPreview').src = "{{ asset('images/') }}/" + el.dataset.image;
            document.getElementById('designTotal').textContent = '₱' + parseFloat(el.dataset.price).toFixed(2);
            document.getElementById('designSummary').innerHTML = `<p><strong>${el.dataset.name}</strong> (Preset)</p>`;
        });
    });

    document.getElementById('addCustomToCart').addEventListener('click', async () => {
        const qty = parseInt(document.getElementById('customQty').value) || 1;
        const totalText = document.getElementById('designTotal').textContent.replace('₱', '');
        const price = parseFloat(totalText) / qty;
        const desc = Array.from(document.querySelectorAll('#designSummary p')).map(p => p.textContent.trim()).join('; ');

        if (price <= 0) {
            alert('Please select at least a flower type first.');
            return;
        }

        const fd = new FormData();
        fd.append('name', selection.flower || 'Custom Flower Arrangement');
        fd.append('price', price);
        fd.append('description', desc);
        fd.append('quantity', qty);

        const res = await fetch("{{ route('cart.addCustom') }}", {
            method: 'POST',
            body: fd,
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
        });
        const data = await res.json();
        if (data.success) {
            alert('Custom design added to cart!');
            if (window.updateCartCount) updateCartCount();
        } else {
            alert(data.message);
        }
    });

    document.getElementById('saveDesign').addEventListener('click', async () => {
        const name = prompt('Name your design:', selection.flower || 'Custom Design');
        if (!name) return;
        const total = parseFloat(document.getElementById('designTotal').textContent.replace('₱', ''));

        const fd = new FormData();
        fd.append('design_name', name);
        fd.append('design_data', JSON.stringify(selection));
        fd.append('total_price', total);

        const res = await fetch("{{ route('customize.save') }}", {
            method: 'POST',
            body: fd,
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
        });
        const data = await res.json();
        if (data.success) {
            alert('Design saved!');
            location.reload();
        } else {
            alert(data.message);
        }
    });

    document.querySelectorAll('.load-design-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            try {
                const data = JSON.parse(btn.dataset.json);
                document.querySelectorAll('.flower-opt').forEach(x => x.style.borderColor = '#eee');
                document.querySelectorAll('.color-opt').forEach(x => x.style.borderColor = '#eee');
                document.querySelectorAll('.style-opt').forEach(x => x.style.borderColor = '#eee');
                document.querySelectorAll('.addon-opt').forEach(x => x.style.borderColor = '#eee');
                selection.flower = data.flower || null;
                selection.color = data.color || null;
                selection.style = data.style || null;
                selection.addons = data.addons || [];
                if (selection.flower) {
                    const el = document.querySelector(`.flower-opt[data-name="${selection.flower}"]`);
                    if (el) el.style.borderColor = 'var(--accent)';
                }
                if (selection.color) {
                    const el = document.querySelector(`.color-opt[data-name="${selection.color}"]`);
                    if (el) el.style.borderColor = 'var(--accent)';
                }
                if (selection.style) {
                    const el = document.querySelector(`.style-opt[data-name="${selection.style}"]`);
                    if (el) el.style.borderColor = 'var(--accent)';
                }
                selection.addons.forEach(name => {
                    const el = document.querySelector(`.addon-opt[data-name="${name}"]`);
                    if (el) el.style.borderColor = 'var(--accent)';
                });
                recompute();
                document.getElementById('designTotal').textContent = '₱' + parseFloat(btn.closest('div').querySelector('p').textContent.replace('₱', '').replace(',', '')).toFixed(2);
            } catch (e) {
                alert('Could not load design.');
            }
        });
    });
</script>
@endpush
