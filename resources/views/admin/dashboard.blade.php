<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard | HappyStem</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="icon" href="{{ asset('images/logo.jpg') }}" type="image/jpeg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        * { box-sizing: border-box; }
        body { background: #f4f0ef; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; color: #5a4a4a; }
        .admin-topbar { background: linear-gradient(135deg, #e8b4bc, #8a9b6e); color: #fff; padding: 14px 30px; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 50; }
        .admin-topbar h2 { margin: 0; font-size: 1.2rem; }
        .admin-topbar a { color: #fff; text-decoration: none; font-weight: 600; }
        .admin-body { display: flex; min-height: calc(100vh - 62px); }
        .admin-nav { width: 210px; background: #fff; box-shadow: 2px 0 10px rgba(0,0,0,0.05); padding: 20px 0; flex-shrink: 0; position: sticky; top: 62px; align-self: flex-start; max-height: calc(100vh - 62px); overflow-y: auto; }
        .admin-nav button { display: block; width: 100%; text-align: left; padding: 12px 24px; background: none; border: none; cursor: pointer; font-size: 0.95rem; color: #5a4a4a; font-weight: 500; border-left: 4px solid transparent; }
        .admin-nav button:hover, .admin-nav button.active { background: #f9f3f4; color: #d17b88; border-left-color: #d17b88; }
        .admin-content { flex: 1; padding: 30px; overflow-x: auto; }
        .tab-panel { display: none; }
        .tab-panel.active { display: block; }
        .card { background: #fff; border-radius: 10px; padding: 22px; box-shadow: 0 4px 15px rgba(0,0,0,0.06); margin-bottom: 22px; }
        .card h3 { color: #8a9b6e; margin: 0 0 16px; }
        .card.collapsible h3 { cursor: pointer; display: flex; justify-content: space-between; align-items: center; gap: 12px; user-select: none; margin-bottom: 16px; }
        .card.collapsible h3::after { content: "\f078"; font-family: "Font Awesome 6 Free"; font-weight: 900; font-size: 0.8rem; color: #8a9b6e; transition: transform 0.25s ease; flex-shrink: 0; }
        .card.collapsible.collapsed h3::after { transform: rotate(-90deg); }
        .card.collapsible.collapsed > *:not(h3) { display: none; }
        table.admin-table { width: 100%; border-collapse: collapse; font-size: 0.88rem; }
        table.admin-table th { background: #f9f3f4; text-align: left; padding: 10px 12px; color: #5a4a4a; }
        table.admin-table td { padding: 10px 12px; border-bottom: 1px solid #f0ebea; }
        table.admin-table img { width: 46px; height: 46px; object-fit: cover; border-radius: 6px; }
        .badge { padding: 3px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
        .badge-pending { background: #fff4e0; color: #b37400; }
        .badge-confirmed { background: #e8f0fe; color: #1a4a8a; }
        .badge-preparing { background: #eaf3fb; color: #1e6f9f; }
        .badge-ready { background: #eef7ee; color: #2e7d32; }
        .badge-delivered { background: #e8f5e9; color: #1e7a2c; }
        .badge-cancelled { background: #fdecea; color: #b3261e; }
        .btn-sm { padding: 6px 12px; border-radius: 20px; border: none; cursor: pointer; font-size: 0.8rem; font-weight: 600; color: #fff; }
        .btn-edit { background: #8a9b6e; }
        .btn-del { background: #c94a4a; }
        .btn-ok { background: #2e7d32; }
        .save-row-btn { background: #c9c9c9; cursor: not-allowed; opacity: 0.85; transition: background 0.2s, opacity 0.2s; }
        .edit-row.dirty .save-row-btn { background: #2e7d32; cursor: pointer; opacity: 1; }
        .btn-verify { background: #1a4a8a; }
        .btn-warn { background: #b37400; }
        .filter-bar { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 16px; align-items: center; }
        .filter-bar input, .filter-bar select { padding: 8px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 0.85rem; }
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-size: 0.92rem; }
        .alert-success { background: #e8f5e9; color: #1e7a2c; border: 1px solid #b7e0bd; }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; margin-bottom: 12px; }
        .card .add-form { padding-bottom: 16px; margin-bottom: 18px; border-bottom: 1px dashed #eee; }
        .form-grid label { display: block; font-size: 0.82rem; color: #5a4a4a; margin-bottom: 5px; font-weight: 500; }
        .form-grid input, .form-grid select, .form-grid textarea { width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 0.88rem; }
        .pagination { display: flex; gap: 8px; margin-top: 16px; flex-wrap: wrap; }
        .pagination a { width: 34px; height: 34px; display: flex; align-items: center; justify-content: center; border-radius: 50%; text-decoration: none; background: #fff; border: 1px solid #e8b4bc; color: #5a4a4a; font-size: 0.85rem; font-weight: 600; }
        .pagination a.active { background: #e8b4bc; color: #fff; }
        .stat-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 14px; margin-bottom: 22px; }
        .stat-card { background: #fff; border-radius: 10px; padding: 18px; box-shadow: 0 4px 15px rgba(0,0,0,0.06); text-align: center; }
        .stat-card .num { font-size: 1.5rem; font-weight: 700; color: #d17b88; }
        .stat-card .lbl { font-size: 0.8rem; color: #5a4a4a; margin-top: 4px; }
        .edit-modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 100; align-items: center; justify-content: center; }
        .edit-modal.show { display: flex; }
        .edit-modal-box { background: #fff; border-radius: 12px; padding: 30px; width: 92%; max-width: 520px; }
        .msg-item { padding: 12px 0; border-bottom: 1px solid #f0ebea; font-size: 0.88rem; }
        .msg-item strong { color: #5a4a4a; }
        .msg-item .msg-meta { font-size: 0.78rem; color: #8a9b6e; margin-top: 2px; }
        .empty-row { text-align: center; padding: 40px 0; color: #8a8a8a; }
        .inline-edit { border: 1px solid transparent; background: transparent; font: inherit; color: inherit; padding: 4px 6px; border-radius: 5px; }
        .inline-edit:hover { border-color: #e8b4bc; background: #fdf7f8; }
        .inline-edit:focus { border-color: #d17b88; background: #fff; outline: none; box-shadow: 0 0 0 2px rgba(209,123,136,0.15); }
        .inline-edit-lg { font-weight: 700; font-size: 0.95rem; min-width: 90px; }
        .inline-edit-sm { font-size: 0.78rem; color: #8a8a8a; min-width: 70px; }
        .inline-edit-num { width: 62px; text-align: center; }
        .inline-edit-num.oos { color: #c0392b; font-weight: 700; }
        .money { font-weight: 600; color: #5a4a4a; }
        .oos-badge { display: block; margin-top: 3px; font-size: 0.68rem; background: #fdecea; color: #c0392b; padding: 1px 8px; border-radius: 10px; text-align: center; }
        .photo-thumb, .flower-thumb { cursor: zoom-in; }
        .photo-thumb-placeholder, .flower-thumb-placeholder { font-size: 1.6rem; color: #8a9b6e; width: 46px; height: 46px; display: flex; align-items: center; justify-content: center; }
        .photo-swatch { width: 46px; height: 46px; border-radius: 50%; border: 2px solid #ddd; cursor: zoom-in; }
        .lightbox-swatch { width: 220px; height: 220px; border-radius: 50%; margin: 0 auto; border: 4px solid #f0ebea; }
        .switch { position: relative; display: inline-block; width: 44px; height: 24px; vertical-align: middle; }
        .switch input { opacity: 0; width: 0; height: 0; }
        .switch .slider { position: absolute; cursor: pointer; inset: 0; background: #c94a4a; transition: 0.25s; border-radius: 24px; }
        .switch .slider::before { content: ""; position: absolute; height: 18px; width: 18px; left: 3px; bottom: 3px; background: #fff; transition: 0.25s; border-radius: 50%; box-shadow: 0 1px 3px rgba(0,0,0,0.3); }
        .switch input:checked + .slider { background: #2e7d32; }
        .switch input:checked + .slider::before { transform: translateX(20px); }
        @media (max-width: 900px) { .admin-body { flex-direction: column; } .admin-nav { width: 100%; display: flex; overflow-x: auto; padding: 8px 0; position: static; align-self: auto; max-height: none; } .admin-nav button { border-left: none; border-bottom: 3px solid transparent; width: auto; padding: 10px 16px; white-space: nowrap; } .admin-nav button.active { border-bottom-color: #d17b88; } }
    </style>
</head>
<body>
    <div class="admin-topbar">
        <h2><i class="fas fa-seedling"></i> HappyStem Admin</h2>
        <div style="display:flex;gap:20px;align-items:center;">
            <span style="font-size:0.85rem;"><i class="fas fa-user-shield"></i> {{ Auth::guard('admin')->user()->username }}</span>
            <form action="{{ route('admin.logout') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" style="background:rgba(255,255,255,0.2);border:none;color:#fff;padding:7px 14px;border-radius:20px;cursor:pointer;font-weight:600;">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <div class="admin-body">
        <div class="admin-nav">
            <button class="tab-btn {{ $activeTab === 'products' ? 'active' : '' }}" data-tab="products"><i class="fas fa-store"></i> Products</button>
            <button class="tab-btn {{ $activeTab === 'customization' ? 'active' : '' }}" data-tab="customization"><i class="fas fa-magic"></i> Customization</button>
            <button class="tab-btn {{ $activeTab === 'categories' ? 'active' : '' }}" data-tab="categories"><i class="fas fa-tags"></i> Categories</button>
            <button class="tab-btn {{ $activeTab === 'services' ? 'active' : '' }}" data-tab="services"><i class="fas fa-images"></i> Services</button>
            <button class="tab-btn {{ $activeTab === 'payments' ? 'active' : '' }}" data-tab="payments"><i class="fas fa-money-check-alt"></i> Payments</button>
            <button class="tab-btn {{ $activeTab === 'orders' ? 'active' : '' }}" data-tab="orders"><i class="fas fa-box-open"></i> Orders</button>
            <button class="tab-btn {{ $activeTab === 'messages' ? 'active' : '' }}" data-tab="messages"><i class="fas fa-envelope"></i> Messages</button>
            <button class="tab-btn {{ $activeTab === 'reports' ? 'active' : '' }}" data-tab="reports"><i class="fas fa-chart-line"></i> Reports</button>
        </div>

        <div class="admin-content">
            @if (session('message'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @endif

            {{-- ─────────── PRODUCTS ─────────── --}}
            <div class="tab-panel {{ $activeTab === 'products' ? 'active' : '' }}" id="tab-products">
                <div class="card collapsible">
                    <h3>Add New Product</h3>
                    <form action="{{ route('admin.dashboard.post') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="action" value="add_product">
                        <div class="form-grid">
                            <div><label>Name</label><input type="text" name="name" required></div>
                            <div><label>Price</label><input type="number" step="0.01" min="0" name="price" required></div>
                            <div>
                                <label>Categories (hold Ctrl/Cmd to select multiple)</label>
                                <select name="categories[]" multiple size="6" required style="min-height:120px;">
                                    @foreach ($categoriesList as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->display_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label>Flowers used (makes this product depend on flower stock)</label>
                                <select name="flowers[]" multiple size="6" style="min-height:120px;">
                                    @foreach ($customFlowers as $flower)
                                        <option value="{{ $flower->id }}">{{ $flower->display_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label>Active</label>
                                <select name="is_active">
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                            <div><label>Product Image</label><input type="file" name="image" accept="image/*"></div>
                            <div style="grid-column: 1 / -1;"><label>Description</label><textarea name="description" rows="2"></textarea></div>
                        </div>
                        <button type="submit" class="btn-sm btn-ok"><i class="fas fa-plus"></i> Add Product</button>
                    </form>
                </div>

                <div class="card collapsible collapsed">
                    <h3>Products ({{ $totalProducts }})</h3>
                    <form method="GET" action="{{ route('admin.dashboard') }}" class="filter-bar">
                        <input type="hidden" name="tab" value="products">
                        <select name="category_filter">
                            <option value="">All categories</option>
                            @foreach ($categoriesList as $cat)
                                <option value="{{ $cat->slug }}" @selected($categoryFilter === $cat->slug)>{{ $cat->display_name }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="search_product" placeholder="Search products..." value="{{ $searchProduct }}">
                        <button type="submit" class="btn-sm btn-verify">Filter</button>
                    </form>

                    <table class="admin-table">
                        <thead>
                            <tr><th></th><th>Name</th><th>Category</th><th>Price</th><th>Availability</th><th>Actions</th></tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                <tr>
                                    <td><img src="{{ asset('images/'.$product->image_url) }}" alt=""></td>
                                    <td><strong>{{ $product->name }}</strong><br><span style="font-size:0.78rem;color:#8a8a8a;">{{ \Illuminate\Support\Str::limit($product->description, 40) }}</span></td>
                                    <td>
                                        @foreach ($product->categories as $cat)
                                            <span style="display:inline-block;background:#f3e8ea;color:var(--secondary);border-radius:20px;padding:2px 10px;font-size:0.75rem;margin:2px 2px 2px 0;">{{ $cat->display_name }}</span>
                                        @endforeach
                                    </td>
                                    <td>₱{{ number_format($product->price, 2) }}</td>
                                    <td>
                                        @if ($product->is_available)
                                            <span style="display:inline-block;background:#e8f5e9;color:#1b7f3b;border-radius:20px;padding:2px 10px;font-size:0.75rem;">Available</span>
                                        @else
                                            <span style="display:inline-block;background:#fdecea;color:#c0392b;border-radius:20px;padding:2px 10px;font-size:0.75rem;" title="Hidden from customers or uses an out-of-stock flower">Unavailable</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn-sm btn-edit edit-product-btn"
                                                data-id="{{ $product->id }}"
                                                data-name="{{ $product->name }}"
                                                data-price="{{ $product->price }}"
                                                data-categories="{{ $product->categories->pluck('id')->implode(',') }}"
                                                data-flowers="{{ $product->flowers->pluck('id')->implode(',') }}"
                                                data-active="{{ $product->is_active ? '1' : '0' }}"
                                                data-image="{{ $product->image_url }}"
                                                data-description="{{ $product->description }}">
                                            Edit
                                        </button>
                                        <form action="{{ route('admin.dashboard.post') }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="action" value="delete_product">
                                            <input type="hidden" name="id" value="{{ $product->id }}">
                                            <button type="submit" class="btn-sm btn-del" onclick="return confirm('Delete this product?');">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="empty-row">No products found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>

                    @if ($totalPages > 1)
                        <div class="pagination">
                            @for ($i = 1; $i <= $totalPages; $i++)
                                <a href="{{ route('admin.dashboard', array_filter(['tab' => 'products', 'category_filter' => $categoryFilter !== '' ? $categoryFilter : null, 'search_product' => $searchProduct !== '' ? $searchProduct : null, 'page' => $i])) }}"
                                   class="{{ $i === $page ? 'active' : '' }}">{{ $i }}</a>
                            @endfor
                        </div>
                    @endif
                </div>
            </div>

            {{-- ─────────── CATEGORIES ─────────── --}}
            <div class="tab-panel {{ $activeTab === 'categories' ? 'active' : '' }}" id="tab-categories">
                <div class="card">
                    <h3>Add New Category</h3>
                    <form action="{{ route('admin.dashboard.post') }}" method="POST" class="filter-bar">
                        @csrf
                        <input type="hidden" name="action" value="add_category">
                        <input type="text" name="cat_display" placeholder="Display name (e.g. Orchids)" required>
                        <button type="submit" class="btn-sm btn-ok">Add Category</button>
                    </form>
                </div>

                <div class="card">
                    <h3>Product Categories</h3>
                    <table class="admin-table">
                        <thead><tr><th>Display Name</th><th>Products</th><th>Actions</th></tr></thead>
                        <tbody>
                            @forelse ($categoriesList as $category)
                                <tr>
                                    <td>
                                        <form action="{{ route('admin.dashboard.post') }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="action" value="edit_category">
                                            <input type="hidden" name="cat_id" value="{{ $category->id }}">
                                            <input type="text" name="cat_display" value="{{ $category->display_name }}" style="padding:6px 10px;border:1px solid #ddd;border-radius:6px;">
                                            <button type="submit" class="btn-sm btn-edit">Save</button>
                                        </form>
                                    </td>
                                    <td>{{ $categoryCounts[$category->slug] ?? 0 }}</td>
                                    <td>
                                        <form action="{{ route('admin.dashboard.post') }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="action" value="delete_category">
                                            <input type="hidden" name="cat_id" value="{{ $category->id }}">
                                            <button type="submit" class="btn-sm btn-del" onclick="return confirm('Delete this category?');">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="empty-row">No categories yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ─────────── SERVICES ─────────── --}}
            <div class="tab-panel {{ $activeTab === 'services' ? 'active' : '' }}" id="tab-services">
                <div class="card collapsible">
                    <h3>Add Service Photo</h3>
                    <form action="{{ route('admin.dashboard.post') }}" method="POST" class="filter-bar" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="action" value="add_service_photo">
                        <select name="category" required>
                            @foreach ($serviceCategories as $cat)
                                <option value="{{ $cat }}">{{ $serviceNames[$cat] }}</option>
                            @endforeach
                        </select>
                        <input type="file" name="image" accept="image/*" required>
                        <input type="text" name="caption" placeholder="Caption (e.g. Bridal Bouquet)">
                        <button type="submit" class="btn-sm btn-ok">Add Photo</button>
                    </form>
                </div>

                @foreach ($serviceCategories as $cat)
                    <div class="card collapsible collapsed">
                        <h3>{{ $serviceNames[$cat] }}</h3>
                        <table class="admin-table">
                            <thead><tr><th></th><th>Image</th><th>Caption</th><th>Action</th></tr></thead>
                            <tbody>
                                @forelse ($servicePhotos->where('category', $cat) as $photo)
                                    <tr>
                                        <td></td>
                                        <td><img src="{{ asset('images/'.$photo->image_url) }}" alt=""></td>
                                        <td>{{ $photo->caption }}</td>
                                        <td>
                                            <button class="btn-sm btn-edit edit-service-photo-btn"
                                                    data-id="{{ $photo->id }}"
                                                    data-category="{{ $photo->category }}"
                                                    data-caption="{{ $photo->caption }}">
                                                Edit
                                            </button>
                                            <form action="{{ route('admin.dashboard.post') }}" method="POST" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="action" value="delete_service_photo">
                                                <input type="hidden" name="id" value="{{ $photo->id }}">
                                                <button type="submit" class="btn-sm btn-del" onclick="return confirm('Delete this photo?');">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="empty-row">No photos in this category.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endforeach
            </div>

            {{-- ─────────── CUSTOMIZATION ─────────── --}}
            <div class="tab-panel {{ $activeTab === 'customization' ? 'active' : '' }}" id="tab-customization">
                <div class="card collapsible collapsed">
                    <h3>Flowers ({{ $customFlowers->count() }})</h3>
                    <form action="{{ route('admin.dashboard.post') }}" method="POST" enctype="multipart/form-data" class="add-form">
                        @csrf
                        <input type="hidden" name="action" value="add_custom_flower">
                        <div class="form-grid">
                            <div><label>Display Name</label><input type="text" name="display_name" placeholder="e.g. Roses" required></div>
                            <div><label>Price (₱)</label><input type="number" step="0.01" min="0" name="price" required></div>
                            <div><label>Stock Qty (0 = out of stock)</label><input type="number" min="0" name="stock_quantity" value="100" required></div>
                            <div><label>Sort Order</label><input type="number" min="0" name="sort_order" value="0"></div>
                            <div><label>Photo</label><input type="file" name="image" accept="image/*"></div>
                            <div>
                                <label>Active</label>
                                <select name="is_active">
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn-sm btn-ok"><i class="fas fa-plus"></i> Add Flower</button>
                    </form>
                    <p style="font-size:0.8rem;color:#8a8a8a;margin:16px 0 14px;">Click any value to edit it, then press <strong>Save</strong>. Click a photo to enlarge it and replace it.</p>
                    <table class="admin-table">
                        <thead><tr><th></th><th>Display Name</th><th>Price</th><th>Stock</th><th>Sort</th><th>Active</th><th>Actions</th></tr></thead>
                        <tbody>
                            @forelse ($flowersPaged as $flower)
                                <tr class="flower-edit-row edit-row" data-id="{{ $flower->id }}" data-form="flowerEditForm" data-delete-form="flowerDeleteForm">
                                    <td>
                                        @if ($flower->image_url)
                                            <img class="photo-thumb" data-input="flower-edit-image"
                                                 src="{{ asset('images/'.$flower->image_url) }}" alt="{{ $flower->display_name }}" title="Click to enlarge / replace">
                                        @else
                                            <i class="fas fa-seedling photo-thumb photo-thumb-placeholder" data-input="flower-edit-image" title="Click to add photo"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <input class="inline-edit inline-edit-lg" type="text" data-field="display_name" value="{{ $flower->display_name }}" title="Click to edit display name">
                                        <br>
                                        <input class="inline-edit inline-edit-sm" type="text" data-field="name" value="{{ $flower->name }}" title="Click to edit name">
                                    </td>
                                    <td>
                                        <span class="money">₱</span><input class="inline-edit inline-edit-num" type="number" step="0.01" min="0" data-field="price" value="{{ $flower->price }}" title="Click to edit price">
                                    </td>
                                    <td>
                                        <input class="inline-edit inline-edit-num {{ (int) $flower->stock_quantity <= 0 ? 'oos' : '' }}" type="number" min="0" data-field="stock_quantity" value="{{ $flower->stock_quantity }}" title="Click to edit stock (0 = out of stock)">
                                        @if ((int) $flower->stock_quantity <= 0)
                                            <span class="oos-badge">Out of stock</span>
                                        @endif
                                    </td>
                                    <td>
                                        <input class="inline-edit inline-edit-num" type="number" min="0" data-field="sort_order" value="{{ $flower->sort_order }}" title="Click to edit sort order">
                                    </td>
                                    <td>
                                        <label class="switch">
                                            <input type="checkbox" class="active-check" @checked($flower->is_active)>
                                            <span class="slider"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <button type="button" class="btn-sm btn-ok save-row-btn"><i class="fas fa-save"></i> Save</button>
                                        <button type="button" class="btn-sm btn-del delete-row-btn"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="empty-row">No flowers yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    @if ($flowersTotalPages > 1)
                        <div class="pagination">
                            @for ($i = 1; $i <= $flowersTotalPages; $i++)
                                <a href="{{ route('admin.dashboard', array_filter(['tab' => 'customization', 'cfpage' => $i])) }}"
                                   class="{{ $i === $flowersPage ? 'active' : '' }}">{{ $i }}</a>
                            @endfor
                        </div>
                    @endif
                </div>

                <div class="card collapsible collapsed">
                    <h3>Flower Variants ({{ $flowerVariantsTotal }})</h3>
                    <p style="font-size:0.8rem;color:#8a8a8a;margin:-8px 0 14px;">A variant price above ₱0 replaces the flower's per-stem price when selected. Leave at ₱0 to keep the flower's base price.</p>
                    <form action="{{ route('admin.dashboard.post') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="action" value="add_variant">
                        <div class="form-grid">
                            <div>
                                <label>Flower</label>
                                <select name="flower_id" required>
                                    @foreach ($customFlowers as $flower)
                                        <option value="{{ $flower->id }}">{{ $flower->display_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label>Variant Type</label>
                                <select name="variant_type" class="variant-type-select" required>
                                    <option value="size">Size</option>
                                    <option value="color">Color</option>
                                </select>
                            </div>
                            <div><label>Name (e.g. Red / Large)</label><input type="text" name="display_name" required></div>
                            <div><label>Price (₱, 0 = keep base)</label><input type="number" step="0.01" min="0" name="price" value="0"></div>
                            <div class="variant-hex-field"><label>Hex Color (for colors)</label><input type="text" name="hex_color" placeholder="#ff5733"></div>
                            <div><label>Photo (sizes; or pattern image for colors)</label><input type="file" name="image" accept="image/*"></div>
                            <div><label>Sort Order</label><input type="number" min="0" name="sort_order" value="0"></div>
                            <div>
                                <label>Active</label>
                                <select name="is_active">
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn-sm btn-ok"><i class="fas fa-plus"></i> Add Variant</button>
                    </form>

                    <table class="admin-table" style="margin-top:16px;">
                        <thead><tr><th></th><th>Flower</th><th>Type</th><th>Name</th><th>Price</th><th>Color / Image</th><th>Sort</th><th>Active</th><th>Actions</th></tr></thead>
                        <tbody>
                            @forelse ($flowerVariantsPaged as $variant)
                                <tr class="variant-edit-row edit-row" data-id="{{ $variant->id }}"
                                    data-form="variantEditForm" data-delete-form="variantDeleteForm"
                                    data-parent-id="{{ $variant->option->id }}" data-type="{{ $variant->variant_type }}">
                                    <td>
                                        @if ($variant->image_url)
                                            <img class="photo-thumb" data-input="variant-edit-image" src="{{ asset('images/'.$variant->image_url) }}" alt="" title="Click to enlarge / replace">
                                        @elseif ($variant->variant_type === 'color' && $variant->hex_color)
                                            <div class="photo-swatch" data-hex="{{ $variant->hex_color }}" data-input="variant-edit-image" title="Click to edit photo"></div>
                                        @else
                                            <i class="fas fa-circle photo-thumb photo-thumb-placeholder" data-input="variant-edit-image" title="Click to add photo"></i>
                                        @endif
                                    </td>
                                    <td>{{ $variant->option->display_name }}</td>
                                    <td>{{ ucfirst($variant->variant_type) }}</td>
                                    <td>
                                        <input class="inline-edit inline-edit-lg" type="text" data-field="display_name" value="{{ $variant->display_name }}" title="Click to edit name">
                                        <br>
                                        <input class="inline-edit inline-edit-sm" type="text" data-field="hex_color" value="{{ $variant->hex_color }}" placeholder="#ff5733" title="Click to edit hex (colors)">
                                    </td>
                                    <td>
                                        <span class="money">₱</span><input class="inline-edit inline-edit-num" type="number" step="0.01" min="0" data-field="price" value="{{ $variant->price }}" title="Click to edit price">
                                    </td>
                                    <td>
                                        @if ($variant->image_url)
                                            <img src="{{ asset('images/'.$variant->image_url) }}" alt="" style="width:26px;height:26px;border-radius:50%;object-fit:cover;border:2px solid #ddd;vertical-align:middle;">
                                        @elseif ($variant->variant_type === 'color' && $variant->hex_color)
                                            <span style="display:inline-block;width:26px;height:26px;border-radius:50%;background:{{ $variant->hex_color }};border:2px solid #ddd;vertical-align:middle;"></span>
                                        @else
                                            <span style="color:#aaa;font-size:0.8rem;">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <input class="inline-edit inline-edit-num" type="number" min="0" data-field="sort_order" value="{{ $variant->sort_order }}" title="Click to edit sort order">
                                    </td>
                                    <td>
                                        <label class="switch">
                                            <input type="checkbox" class="active-check" @checked($variant->is_active)>
                                            <span class="slider"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <button type="button" class="btn-sm btn-ok save-row-btn"><i class="fas fa-save"></i> Save</button>
                                        <button type="button" class="btn-sm btn-del delete-row-btn"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="9" class="empty-row">No flower variants yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    @if ($flowerVariantsTotalPages > 1)
                        <div class="pagination">
                            @for ($i = 1; $i <= $flowerVariantsTotalPages; $i++)
                                <a href="{{ route('admin.dashboard', array_filter(['tab' => 'customization', 'fvpage' => $i])) }}"
                                   class="{{ $i === $flowerVariantsPage ? 'active' : '' }}">{{ $i }}</a>
                            @endfor
                        </div>
                    @endif
                </div>

                <div class="card collapsible collapsed">
                    <h3>Fillers ({{ $fillersTotal }})</h3>
                    <p style="font-size:0.8rem;color:#8a8a8a;margin:-8px 0 14px;">Customers can pick each filler only once (but may pick several different fillers).</p>
                    <form action="{{ route('admin.dashboard.post') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="action" value="add_filler">
                        <div class="form-grid">
                            <div><label>Display Name</label><input type="text" name="display_name" placeholder="e.g. Eucalyptus" required></div>
                            <div><label>Price (₱)</label><input type="number" step="0.01" min="0" name="price" value="0" required></div>
                            <div><label>Sort Order</label><input type="number" min="0" name="sort_order" value="0"></div>
                            <div><label>Photo</label><input type="file" name="image" accept="image/*"></div>
                            <div>
                                <label>Active</label>
                                <select name="is_active">
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn-sm btn-ok"><i class="fas fa-plus"></i> Add Filler</button>
                    </form>

                    <table class="admin-table" style="margin-top:16px;">
                        <thead><tr><th></th><th>Display Name</th><th>Price</th><th>Sort</th><th>Active</th><th>Actions</th></tr></thead>
                        <tbody>
                            @forelse ($fillersPaged as $filler)
                                <tr class="filler-edit-row edit-row" data-id="{{ $filler->id }}" data-form="fillerEditForm" data-delete-form="fillerDeleteForm">
                                    <td>
                                        @if ($filler->image_url)
                                            <img class="photo-thumb" data-input="filler-edit-image" src="{{ asset('images/'.$filler->image_url) }}" alt="" title="Click to enlarge / replace">
                                        @else
                                            <i class="fas fa-leaf photo-thumb photo-thumb-placeholder" data-input="filler-edit-image" title="Click to add photo"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <input class="inline-edit inline-edit-lg" type="text" data-field="display_name" value="{{ $filler->display_name }}" title="Click to edit display name">
                                        <br>
                                        <input class="inline-edit inline-edit-sm" type="text" data-field="name" value="{{ $filler->name }}" title="Click to edit name">
                                    </td>
                                    <td>
                                        <span class="money">₱</span><input class="inline-edit inline-edit-num" type="number" step="0.01" min="0" data-field="price" value="{{ $filler->price }}" title="Click to edit price">
                                    </td>
                                    <td>
                                        <input class="inline-edit inline-edit-num" type="number" min="0" data-field="sort_order" value="{{ $filler->sort_order }}" title="Click to edit sort order">
                                    </td>
                                    <td>
                                        <label class="switch">
                                            <input type="checkbox" class="active-check" @checked($filler->is_active)>
                                            <span class="slider"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <button type="button" class="btn-sm btn-ok save-row-btn"><i class="fas fa-save"></i> Save</button>
                                        <button type="button" class="btn-sm btn-del delete-row-btn"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="empty-row">No fillers yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    @if ($fillersTotalPages > 1)
                        <div class="pagination">
                            @for ($i = 1; $i <= $fillersTotalPages; $i++)
                                <a href="{{ route('admin.dashboard', array_filter(['tab' => 'customization', 'fpage' => $i])) }}"
                                   class="{{ $i === $fillersPage ? 'active' : '' }}">{{ $i }}</a>
                            @endfor
                        </div>
                    @endif
                </div>

                <div class="card collapsible collapsed">
                    <h3>Wrapper Colors ({{ $customColors->count() }})</h3>
                    <form action="{{ route('admin.dashboard.post') }}" method="POST" enctype="multipart/form-data" class="add-form">
                        @csrf
                        <input type="hidden" name="action" value="add_custom_color">
                        <div class="form-grid">
                            <div><label>Display Name</label><input type="text" name="display_name" placeholder="e.g. Red" required></div>
                            <div><label>Price (₱)</label><input type="number" step="0.01" min="0" name="price" value="0"></div>
                            <div><label>Hex Color (e.g. #ff5733)</label><input type="text" name="hex_color" placeholder="#ff5733"></div>
                            <div><label>Pattern Image (optional — overrides hex)</label><input type="file" name="image" accept="image/*"></div>
                            <div><label>Sort Order</label><input type="number" min="0" name="sort_order" value="0"></div>
                            <div>
                                <label>Active</label>
                                <select name="is_active">
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn-sm btn-ok"><i class="fas fa-plus"></i> Add Wrapper Color</button>
                    </form>
                    <table class="admin-table">
                        <thead><tr><th></th><th>Display Name</th><th>Color</th><th>Price</th><th>Sort</th><th>Active</th><th>Actions</th></tr></thead>
                        <tbody>
                            @forelse ($colorsPaged as $color)
                                @php
                                    $swatchMap = ['red' => '#e74c3c', 'pink' => '#e8b4bc', 'white' => '#f9f3f4', 'yellow' => '#f1c40f', 'purple' => '#9b59b6'];
                                    $swatchBg = $color->hex_color ?: ($swatchMap[$color->name] ?? 'linear-gradient(45deg,#e74c3c,#e8b4bc,#f1c40f,#9b59b6)');
                                @endphp
                                <tr class="color-edit-row edit-row" data-id="{{ $color->id }}" data-form="colorEditForm" data-delete-form="colorDeleteForm">
                                    <td>
                                        @if ($color->image_url)
                                            <img class="photo-thumb" data-input="color-edit-image" data-clear-input="color-edit-clear" src="{{ asset('images/'.$color->image_url) }}" alt="" title="Click to enlarge / replace">
                                        @elseif ($color->hex_color)
                                            <div class="photo-swatch" data-hex="{{ $color->hex_color }}" data-input="color-edit-image" data-clear-input="color-edit-clear" title="Click to edit photo"></div>
                                        @else
                                            <div class="photo-swatch" data-input="color-edit-image" data-clear-input="color-edit-clear" style="background:{{ $swatchBg }};" title="Click to edit photo"></div>
                                        @endif
                                    </td>
                                    <td>
                                        <input class="inline-edit inline-edit-lg" type="text" data-field="display_name" value="{{ $color->display_name }}" title="Click to edit display name">
                                        <br>
                                        <input class="inline-edit inline-edit-sm" type="text" data-field="name" value="{{ $color->name }}" title="Click to edit name">
                                    </td>
                                    <td>
                                        <input class="inline-edit inline-edit-sm" type="text" data-field="hex_color" value="{{ $color->hex_color }}" placeholder="#ff5733" title="Click to edit hex color">
                                    </td>
                                    <td>
                                        <span class="money">₱</span><input class="inline-edit inline-edit-num" type="number" step="0.01" min="0" data-field="price" value="{{ $color->price }}" title="Click to edit price">
                                    </td>
                                    <td>
                                        <input class="inline-edit inline-edit-num" type="number" min="0" data-field="sort_order" value="{{ $color->sort_order }}" title="Click to edit sort order">
                                    </td>
                                    <td>
                                        <label class="switch">
                                            <input type="checkbox" class="active-check" @checked($color->is_active)>
                                            <span class="slider"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <button type="button" class="btn-sm btn-ok save-row-btn"><i class="fas fa-save"></i> Save</button>
                                        <button type="button" class="btn-sm btn-del delete-row-btn"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="empty-row">No wrapper colors yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    @if ($colorsTotalPages > 1)
                        <div class="pagination">
                            @for ($i = 1; $i <= $colorsTotalPages; $i++)
                                <a href="{{ route('admin.dashboard', array_filter(['tab' => 'customization', 'cpage' => $i])) }}"
                                   class="{{ $i === $colorsPage ? 'active' : '' }}">{{ $i }}</a>
                            @endfor
                        </div>
                    @endif
                </div>

                <div class="card collapsible collapsed">
                    <h3>Ribbons ({{ $customRibbons->count() }})</h3>
                    <form action="{{ route('admin.dashboard.post') }}" method="POST" enctype="multipart/form-data" class="add-form">
                        @csrf
                        <input type="hidden" name="action" value="add_ribbon">
                        <div class="form-grid">
                            <div><label>Display Name</label><input type="text" name="display_name" placeholder="e.g. Satin Ribbon" required></div>
                            <div><label>Price (₱, 0 if set per size/color)</label><input type="number" step="0.01" min="0" name="price" value="0"></div>
                            <div><label>Photo (optional)</label><input type="file" name="image" accept="image/*"></div>
                            <div><label>Sort Order</label><input type="number" min="0" name="sort_order" value="0"></div>
                            <div>
                                <label>Active</label>
                                <select name="is_active">
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn-sm btn-ok"><i class="fas fa-plus"></i> Add Ribbon</button>
                    </form>
                    <table class="admin-table">
                        <thead><tr><th></th><th>Display Name</th><th>Price</th><th>Sort</th><th>Active</th><th>Actions</th></tr></thead>
                        <tbody>
                            @forelse ($ribbonsPaged as $ribbon)
                                <tr class="ribbon-edit-row edit-row" data-id="{{ $ribbon->id }}" data-form="ribbonEditForm" data-delete-form="ribbonDeleteForm">
                                    <td>
                                        @if ($ribbon->image_url)
                                            <img class="photo-thumb" data-input="ribbon-edit-image" data-clear-input="ribbon-edit-clear" src="{{ asset('images/'.$ribbon->image_url) }}" alt="" title="Click to enlarge / replace">
                                        @else
                                            <i class="fas fa-ribbon photo-thumb photo-thumb-placeholder" data-input="ribbon-edit-image" data-clear-input="ribbon-edit-clear" title="Click to add photo"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <input class="inline-edit inline-edit-lg" type="text" data-field="display_name" value="{{ $ribbon->display_name }}" title="Click to edit display name">
                                        <br>
                                        <input class="inline-edit inline-edit-sm" type="text" data-field="name" value="{{ $ribbon->name }}" title="Click to edit name">
                                    </td>
                                    <td>
                                        <span class="money">₱</span><input class="inline-edit inline-edit-num" type="number" step="0.01" min="0" data-field="price" value="{{ $ribbon->price }}" title="Click to edit price">
                                    </td>
                                    <td>
                                        <input class="inline-edit inline-edit-num" type="number" min="0" data-field="sort_order" value="{{ $ribbon->sort_order }}" title="Click to edit sort order">
                                    </td>
                                    <td>
                                        <label class="switch">
                                            <input type="checkbox" class="active-check" @checked($ribbon->is_active)>
                                            <span class="slider"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <button type="button" class="btn-sm btn-ok save-row-btn"><i class="fas fa-save"></i> Save</button>
                                        <button type="button" class="btn-sm btn-del delete-row-btn"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="empty-row">No ribbons yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    @if ($ribbonsTotalPages > 1)
                        <div class="pagination">
                            @for ($i = 1; $i <= $ribbonsTotalPages; $i++)
                                <a href="{{ route('admin.dashboard', array_filter(['tab' => 'customization', 'rpage' => $i])) }}"
                                   class="{{ $i === $ribbonsPage ? 'active' : '' }}">{{ $i }}</a>
                            @endfor
                        </div>
                    @endif
                </div>

                <div class="card collapsible collapsed">
                    <h3>Ribbon Variants ({{ $ribbonVariantsTotal }})</h3>
                    <p style="font-size:0.8rem;color:#8a8a8a;margin:-8px 0 14px;">Add a Color (hex or pattern image) or a Size (e.g. 1 inch) to each ribbon. If the size price is ₱0 the color price is used.</p>
                    <form action="{{ route('admin.dashboard.post') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="action" value="add_variant">
                        <div class="form-grid">
                            <div>
                                <label>Ribbon</label>
                                <select name="flower_id" required>
                                    @foreach ($customRibbons as $ribbon)
                                        <option value="{{ $ribbon->id }}">{{ $ribbon->display_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label>Variant Type</label>
                                <select name="variant_type" class="variant-type-select" required>
                                    <option value="color">Color</option>
                                    <option value="size">Size</option>
                                </select>
                            </div>
                            <div><label>Name (e.g. Red / 1 inch)</label><input type="text" name="display_name" required></div>
                            <div><label>Price (₱, 0 = free)</label><input type="number" step="0.01" min="0" name="price" value="0"></div>
                            <div class="variant-hex-field"><label>Hex Color (for colors)</label><input type="text" name="hex_color" placeholder="#ff5733"></div>
                            <div><label>Pattern Image (optional — overrides hex)</label><input type="file" name="image" accept="image/*"></div>
                            <div><label>Sort Order</label><input type="number" min="0" name="sort_order" value="0"></div>
                            <div>
                                <label>Active</label>
                                <select name="is_active">
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn-sm btn-ok"><i class="fas fa-plus"></i> Add Ribbon Variant</button>
                    </form>

                    <table class="admin-table" style="margin-top:16px;">
                        <thead><tr><th></th><th>Ribbon</th><th>Type</th><th>Name</th><th>Price</th><th>Color / Image</th><th>Sort</th><th>Active</th><th>Actions</th></tr></thead>
                        <tbody>
                            @forelse ($ribbonVariantsPaged as $variant)
                                <tr class="variant-edit-row edit-row" data-id="{{ $variant->id }}"
                                    data-form="variantEditForm" data-delete-form="variantDeleteForm"
                                    data-parent-id="{{ $variant->option->id }}" data-type="{{ $variant->variant_type }}">
                                    <td>
                                        @if ($variant->image_url)
                                            <img class="photo-thumb" data-input="variant-edit-image" src="{{ asset('images/'.$variant->image_url) }}" alt="" title="Click to enlarge / replace">
                                        @elseif ($variant->variant_type === 'color' && $variant->hex_color)
                                            <div class="photo-swatch" data-hex="{{ $variant->hex_color }}" data-input="variant-edit-image" title="Click to edit photo"></div>
                                        @else
                                            <i class="fas fa-circle photo-thumb photo-thumb-placeholder" data-input="variant-edit-image" title="Click to add photo"></i>
                                        @endif
                                    </td>
                                    <td>{{ $variant->option->display_name }}</td>
                                    <td>{{ ucfirst($variant->variant_type) }}</td>
                                    <td>
                                        <input class="inline-edit inline-edit-lg" type="text" data-field="display_name" value="{{ $variant->display_name }}" title="Click to edit name">
                                        <br>
                                        <input class="inline-edit inline-edit-sm" type="text" data-field="hex_color" value="{{ $variant->hex_color }}" placeholder="#ff5733" title="Click to edit hex (colors)">
                                    </td>
                                    <td>
                                        <span class="money">₱</span><input class="inline-edit inline-edit-num" type="number" step="0.01" min="0" data-field="price" value="{{ $variant->price }}" title="Click to edit price">
                                    </td>
                                    <td>
                                        @if ($variant->image_url)
                                            <img src="{{ asset('images/'.$variant->image_url) }}" alt="" style="width:26px;height:26px;border-radius:50%;object-fit:cover;border:2px solid #ddd;vertical-align:middle;">
                                        @elseif ($variant->variant_type === 'color' && $variant->hex_color)
                                            <span style="display:inline-block;width:26px;height:26px;border-radius:50%;background:{{ $variant->hex_color }};border:2px solid #ddd;vertical-align:middle;"></span>
                                        @else
                                            <span style="color:#aaa;font-size:0.8rem;">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <input class="inline-edit inline-edit-num" type="number" min="0" data-field="sort_order" value="{{ $variant->sort_order }}" title="Click to edit sort order">
                                    </td>
                                    <td>
                                        <label class="switch">
                                            <input type="checkbox" class="active-check" @checked($variant->is_active)>
                                            <span class="slider"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <button type="button" class="btn-sm btn-ok save-row-btn"><i class="fas fa-save"></i> Save</button>
                                        <button type="button" class="btn-sm btn-del delete-row-btn"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="9" class="empty-row">No ribbon variants yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    @if ($ribbonVariantsTotalPages > 1)
                        <div class="pagination">
                            @for ($i = 1; $i <= $ribbonVariantsTotalPages; $i++)
                                <a href="{{ route('admin.dashboard', array_filter(['tab' => 'customization', 'rvpage' => $i])) }}"
                                   class="{{ $i === $ribbonVariantsPage ? 'active' : '' }}">{{ $i }}</a>
                            @endfor
                        </div>
                    @endif
                </div>

                <div class="card collapsible collapsed">
                    <h3>Styles ({{ $customStyles->count() }})</h3>
                    <form action="{{ route('admin.dashboard.post') }}" method="POST" enctype="multipart/form-data" class="add-form">
                        @csrf
                        <input type="hidden" name="action" value="add_custom_style">
                        <div class="form-grid">
                            <div><label>Display Name</label><input type="text" name="display_name" placeholder="e.g. Hand-Tied Bouquet" required></div>
                            <div><label>Price (₱)</label><input type="number" step="0.01" min="0" name="price" required></div>
                            <div><label>Sort Order</label><input type="number" min="0" name="sort_order" value="0"></div>
                            <div><label>Photo</label><input type="file" name="image" accept="image/*"></div>
                            <div>
                                <label>Active</label>
                                <select name="is_active">
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn-sm btn-ok"><i class="fas fa-plus"></i> Add Style</button>
                    </form>
                    <table class="admin-table">
                        <thead><tr><th></th><th>Display Name</th><th>Price</th><th>Sort</th><th>Active</th><th>Actions</th></tr></thead>
                        <tbody>
                            @forelse ($stylesPaged as $style)
                                <tr class="style-edit-row edit-row" data-id="{{ $style->id }}" data-form="styleEditForm" data-delete-form="styleDeleteForm">
                                    <td>
                                        @if ($style->image_url)
                                            <img class="photo-thumb" data-input="style-edit-image" src="{{ asset('images/'.$style->image_url) }}" alt="" title="Click to enlarge / replace">
                                        @else
                                            <i class="fas fa-seedling photo-thumb photo-thumb-placeholder" data-input="style-edit-image" title="Click to add photo"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <input class="inline-edit inline-edit-lg" type="text" data-field="display_name" value="{{ $style->display_name }}" title="Click to edit display name">
                                        <br>
                                        <input class="inline-edit inline-edit-sm" type="text" data-field="name" value="{{ $style->name }}" title="Click to edit name">
                                    </td>
                                    <td>
                                        <span class="money">₱</span><input class="inline-edit inline-edit-num" type="number" step="0.01" min="0" data-field="price" value="{{ $style->price }}" title="Click to edit price">
                                    </td>
                                    <td>
                                        <input class="inline-edit inline-edit-num" type="number" min="0" data-field="sort_order" value="{{ $style->sort_order }}" title="Click to edit sort order">
                                    </td>
                                    <td>
                                        <label class="switch">
                                            <input type="checkbox" class="active-check" @checked($style->is_active)>
                                            <span class="slider"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <button type="button" class="btn-sm btn-ok save-row-btn"><i class="fas fa-save"></i> Save</button>
                                        <button type="button" class="btn-sm btn-del delete-row-btn"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="empty-row">No styles yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    @if ($stylesTotalPages > 1)
                        <div class="pagination">
                            @for ($i = 1; $i <= $stylesTotalPages; $i++)
                                <a href="{{ route('admin.dashboard', array_filter(['tab' => 'customization', 'spage' => $i])) }}"
                                   class="{{ $i === $stylesPage ? 'active' : '' }}">{{ $i }}</a>
                            @endfor
                        </div>
                    @endif
                </div>
            </div>

            {{-- ─────────── PAYMENTS ─────────── --}}
            <div class="tab-panel {{ $activeTab === 'payments' ? 'active' : '' }}" id="tab-payments">
                <div class="card">
                    <h3>Pending GCash Payments ({{ $pendingPayments->count() }})</h3>
                    <table class="admin-table">
                        <thead><tr><th>Order</th><th>Customer</th><th>Ref #</th><th>Amount</th><th>Screenshot</th><th>Date</th><th>Actions</th></tr></thead>
                        <tbody>
                            @forelse ($pendingPayments as $payment)
                                <tr>
                                    <td><a href="{{ route('orders.show', $payment->order_id) }}" style="color:var(--accent);font-weight:600;">{{ $payment->order_number }}</a></td>
                                    <td>{{ $payment->full_name }}<br><span style="font-size:0.78rem;color:#8a8a8a;">{{ $payment->email }}</span></td>
                                    <td>{{ $payment->reference_number }}</td>
                                    <td>₱{{ number_format($payment->amount, 2) }}</td>
                                    <td>
                                        @if ($payment->screenshot_path)
                                            <a href="{{ asset($payment->screenshot_path) }}" target="_blank">View</a>
                                        @else
                                            <span style="color:#8a8a8a;">None</span>
                                        @endif
                                    </td>
                                    <td>{{ $payment->created_at }}</td>
                                    <td>
                                        <form action="{{ route('admin.dashboard.post') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="action" value="verify_gcash">
                                            <input type="hidden" name="payment_id" value="{{ $payment->id }}">
                                            <input type="hidden" name="order_id" value="{{ $payment->order_id }}">
                                            <button type="submit" class="btn-sm btn-ok" onclick="return confirm('Verify this payment?');">Verify</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="empty-row">No pending payments.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ─────────── ORDERS ─────────── --}}
            <div class="tab-panel {{ $activeTab === 'orders' ? 'active' : '' }}" id="tab-orders">
                <div class="card">
                    <h3>Orders ({{ $totalOrders }})</h3>
                    <form method="GET" action="{{ route('admin.dashboard') }}" class="filter-bar">
                        <input type="hidden" name="tab" value="orders">
                        <select name="order_status">
                            <option value="">All statuses</option>
                            @foreach (['pending', 'confirmed', 'preparing', 'ready', 'delivered', 'cancelled'] as $st)
                                <option value="{{ $st }}" @selected($orderStatusFilter === $st)>{{ ucfirst($st) }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="order_search" placeholder="Order # or customer..." value="{{ $orderSearch }}">
                        <input type="date" name="order_date_from" value="{{ $orderDateFrom }}">
                        <input type="date" name="order_date_to" value="{{ $orderDateTo }}">
                        <button type="submit" class="btn-sm btn-verify">Filter</button>
                    </form>

                    <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:15px;">
                        @foreach (['pending', 'confirmed', 'preparing', 'ready', 'delivered', 'cancelled'] as $st)
                            <span class="badge badge-{{ $st }}">{{ ucfirst($st) }}: {{ $orderStatusCounts[$st] ?? 0 }}</span>
                        @endforeach
                    </div>

                    <table class="admin-table">
                        <thead><tr><th>Order #</th><th>Customer</th><th>Total</th><th>Payment</th><th>Status</th><th>Delivery</th><th>Actions</th></tr></thead>
                        <tbody>
                            @forelse ($orders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('orders.show', $order->id) }}" style="color:var(--accent);font-weight:600;">{{ $order->order_number }}</a>
                                        <br><span style="font-size:0.78rem;color:#8a8a8a;">{{ $order->created_at }}</span>
                                    </td>
                                    <td>{{ $order->full_name }}<br><span style="font-size:0.78rem;color:#8a8a8a;">{{ $order->phone }}</span></td>
                                    <td>₱{{ number_format($order->total_amount, 2) }}</td>
                                    <td>{{ strtoupper($order->payment_method) }}<br>
                                        <span class="badge {{ $order->payment_status === 'completed' ? 'badge-delivered' : 'badge-pending' }}">{{ str_replace('_', ' ', $order->payment_status) }}</span>
                                    </td>
                                    <td><span class="badge badge-{{ $order->order_status }}">{{ ucfirst($order->order_status) }}</span></td>
                                    <td>{{ $order->delivery_date }}<br><span style="font-size:0.78rem;color:#8a8a8a;">{{ $order->municipality }}</span></td>
                                    <td style="min-width:180px;">
                                        <form action="{{ route('admin.dashboard.post') }}" method="POST" style="display:flex;gap:6px;flex-wrap:wrap;">
                                            @csrf
                                            @if ($order->order_status === 'pending')
                                                <input type="hidden" name="action" value="approve_order">
                                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                <button type="submit" class="btn-sm btn-ok">Approve</button>
                                            @endif
                                        </form>
                                        @if ($order->order_status === 'pending')
                                            <form action="{{ route('admin.dashboard.post') }}" method="POST" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="action" value="decline_order">
                                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                <button type="submit" class="btn-sm btn-del" onclick="return confirm('Decline this order?');">Decline</button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.dashboard.post') }}" method="POST" style="display:inline;margin-left:6px;">
                                            @csrf
                                            <input type="hidden" name="action" value="update_order_status">
                                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                                            <select name="new_status" onchange="saveDashboardState(); this.form.submit()" style="padding:6px;border:1px solid #ddd;border-radius:6px;font-size:0.8rem;">
                                                @foreach (['confirmed', 'preparing', 'ready', 'delivered', 'cancelled'] as $st)
                                                    <option value="{{ $st }}" @selected($order->order_status === $st)>{{ ucfirst($st) }}</option>
                                                @endforeach
                                            </select>
                                        </form>
                                        @if ($order->payment_method === 'gcash' && $order->payment_status !== 'completed')
                                            <form action="{{ route('admin.dashboard.post') }}" method="POST" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="action" value="mark_paid">
                                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                <button type="submit" class="btn-sm btn-ok" onclick="return confirm('Mark as fully paid?');">Mark Paid</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="empty-row">No orders found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>

                    @if ($ordersTotalPages > 1)
                        <div class="pagination">
                            @for ($i = 1; $i <= $ordersTotalPages; $i++)
                                <a href="{{ route('admin.dashboard', array_filter(['tab' => 'orders', 'order_status' => $orderStatusFilter !== '' ? $orderStatusFilter : null, 'order_search' => $orderSearch !== '' ? $orderSearch : null, 'order_date_from' => $orderDateFrom !== '' ? $orderDateFrom : null, 'order_date_to' => $orderDateTo !== '' ? $orderDateTo : null, 'opage' => $i])) }}"
                                   class="{{ $i === $ordersPage ? 'active' : '' }}">{{ $i }}</a>
                            @endfor
                        </div>
                    @endif
                </div>
            </div>

            {{-- ─────────── MESSAGES ─────────── --}}
            <div class="tab-panel {{ $activeTab === 'messages' ? 'active' : '' }}" id="tab-messages">
                <div class="card">
                    <h3>Contact Messages ({{ $messages->count() }})</h3>
                    @forelse ($messages as $msg)
                        <div class="msg-item">
                            <strong>{{ $msg->name }}</strong> &lt;{{ $msg->email }}&gt;
                            <div class="msg-meta">{{ $msg->created_at }}</div>
                            <p style="margin-top:6px;">{{ $msg->message }}</p>
                        </div>
                    @empty
                        <p class="empty-row">No messages yet.</p>
                    @endforelse
                </div>
            </div>

            {{-- ─────────── REPORTS ─────────── --}}
            <div class="tab-panel {{ $activeTab === 'reports' ? 'active' : '' }}" id="tab-reports">
                <style>
                    @media print {
                        body * { visibility: hidden; }
                        #printArea, #printArea * { visibility: visible; }
                        #printArea { position: absolute; top: 0; left: 0; width: 100%; }
                        .no-print { display: none !important; }
                        .stat-card { box-shadow: none !important; border: 1px solid #ddd !important; }
                    }
                    .print-btn { padding:10px 24px; background:var(--secondary); color:white; border:none; border-radius:8px; cursor:pointer; font-weight:600; font-size:0.9rem; }
                    .print-btn:hover { background:#6a7a55; }
                    .report-header { text-align:center; margin-bottom:20px; padding-bottom:15px; border-bottom:2px solid var(--primary); }
                </style>
                <div class="card no-print">
                    <h3>Sales Reports</h3>
                    <form method="GET" action="{{ route('admin.dashboard') }}" class="filter-bar">
                        <input type="hidden" name="tab" value="reports">
                        <select name="report_period">
                            @foreach (['daily' => 'Daily', 'weekly' => 'Weekly', 'monthly' => 'Monthly', 'annual' => 'Annual'] as $val => $lbl)
                                <option value="{{ $val }}" @selected($reportPeriod === $val)>{{ $lbl }}</option>
                            @endforeach
                        </select>
                        @if ($reportPeriod === 'daily')
                            <input type="date" name="report_day" value="{{ $reportDay }}">
                        @elseif ($reportPeriod === 'weekly')
                            <input type="number" name="report_year" value="{{ $reportYear }}" min="2020" max="2100" style="width:90px;">
                            <input type="number" name="report_week" value="{{ $reportWeek }}" min="1" max="53" style="width:80px;" placeholder="Week #">
                        @elseif ($reportPeriod === 'monthly')
                            <input type="number" name="report_year" value="{{ $reportYear }}" min="2020" max="2100" style="width:90px;">
                            <select name="report_month">
                                @for ($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" @selected($reportMonth === $m)>{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                                @endfor
                            </select>
                        @else
                            <input type="number" name="report_year" value="{{ $reportYear }}" min="2020" max="2100" style="width:90px;">
                        @endif
                        <button type="submit" class="btn-sm btn-verify">Generate</button>
                    </form>
                </div>

                <div id="printArea">
                    <div class="report-header">
                        <h2 style="color:var(--secondary);margin:0 0 4px;">HappyStem by Carmencita</h2>
                        <div style="font-size:1.1rem;font-weight:600;color:#555;">Sales Report — {{ $periodLabel }}</div>
                        <div style="font-size:0.82rem;color:#aaa;margin-top:4px;">Generated: {{ now()->format('F j, Y g:i A') }}</div>
                    </div>

                <div class="stat-cards">
                    <div class="stat-card"><div class="num">₱{{ number_format($reportSummary->total_sales ?? 0, 2) }}</div><div class="lbl">Total Sales</div></div>
                    <div class="stat-card"><div class="num">₱{{ number_format($reportSummary->product_sales ?? 0, 2) }}</div><div class="lbl">Product Sales</div></div>
                    <div class="stat-card"><div class="num">₱{{ number_format($reportSummary->total_delivery ?? 0, 2) }}</div><div class="lbl">Delivery Fees</div></div>
                    <div class="stat-card"><div class="num">{{ $reportSummary->total_orders ?? 0 }}</div><div class="lbl">Total Orders</div></div>
                    <div class="stat-card"><div class="num">{{ $reportSummary->delivered ?? 0 }}</div><div class="lbl">Delivered</div></div>
                    <div class="stat-card"><div class="num">{{ $reportSummary->cancelled ?? 0 }}</div><div class="lbl">Cancelled</div></div>
                    <div class="stat-card"><div class="num">₱{{ number_format($reportSummary->gcash_sales ?? 0, 2) }}</div><div class="lbl">GCash Sales ({{ $reportSummary->gcash_orders ?? 0 }})</div></div>
                    <div class="stat-card"><div class="num">₱{{ number_format($reportSummary->cod_sales ?? 0, 2) }}</div><div class="lbl">COD Sales ({{ $reportSummary->cod_orders ?? 0 }})</div></div>
                </div>

                <div class="card">
                    <h3>Top Products</h3>
                    <table class="admin-table">
                        <thead><tr><th>#</th><th>Product</th><th>Quantity Sold</th><th>Revenue</th></tr></thead>
                        <tbody>
                            @forelse ($topProducts as $i => $row)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $row->product_name }}</td>
                                    <td>{{ $row->total_qty }}</td>
                                    <td>₱{{ number_format($row->total_revenue, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="empty-row">No sales in this period.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card">
                    <h3>Sales by Municipality</h3>
                    <table class="admin-table">
                        <thead><tr><th>Municipality</th><th>Orders</th><th>Delivery Collected</th><th>Total Sales</th></tr></thead>
                        <tbody>
                            @forelse ($muniBreakdown as $row)
                                <tr>
                                    <td>{{ $row->municipality }}</td>
                                    <td>{{ $row->order_count }}</td>
                                    <td>₱{{ number_format($row->delivery_collected, 2) }}</td>
                                    <td>₱{{ number_format($row->total_sales, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="empty-row">No data for this period.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($trend->isNotEmpty())
                    <div class="card">
                        <h3>Sales Trend</h3>
                        <table class="admin-table">
                            <thead><tr><th>Period</th><th>Orders</th><th>Sales</th></tr></thead>
                            <tbody>
                                @foreach ($trend as $row)
                                    <tr>
                                        <td>{{ $row->period }}</td>
                                        <td>{{ $row->orders }}</td>
                                        <td>₱{{ number_format($row->sales, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
                </div>

                <div class="no-print" style="text-align:center;margin-top:10px;">
                    <button class="print-btn" onclick="window.print()">🖨️ Print / Save as PDF</button>
                </div>
            </div>
        </div>
    </div>

    <div class="edit-modal" id="editProductModal">
        <div class="edit-modal-box">
            <h3 style="color:var(--secondary);margin-bottom:16px;">Edit Product</h3>
            <form action="{{ route('admin.dashboard.post') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="action" value="edit_product">
                <input type="hidden" name="id" id="edit-id">
                <div class="form-grid">
                    <div><label>Name</label><input type="text" name="name" id="edit-name" required></div>
                    <div><label>Price</label><input type="number" step="0.01" min="0" name="price" id="edit-price" required></div>
                    <div>
                        <label>Categories (hold Ctrl/Cmd to select multiple)</label>
                        <select name="categories[]" id="edit-categories" multiple size="6" required style="min-height:120px;">
                            @foreach ($categoriesList as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->display_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label>Flowers used</label>
                        <select name="flowers[]" id="edit-flowers" multiple size="6" style="min-height:120px;">
                            @foreach ($customFlowers as $flower)
                                <option value="{{ $flower->id }}">{{ $flower->display_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label>Active</label>
                        <select name="is_active" id="edit-active">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <div><label>Image</label><input type="file" name="image" accept="image/*"></div>
                    <input type="hidden" name="image_url" id="edit-image">
                    <div style="grid-column: 1 / -1;"><label>Description</label><textarea name="description" id="edit-description" rows="3"></textarea></div>
                </div>
                <div style="display:flex;gap:10px;margin-top:14px;">
                    <button type="submit" class="btn-sm btn-ok">Save Changes</button>
                    <button type="button" class="btn-sm btn-del" onclick="document.getElementById('editProductModal').classList.remove('show');">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Hidden form used by the inline-edit flowers table --}}
    <form id="flowerEditForm" action="{{ route('admin.dashboard.post') }}" method="POST" enctype="multipart/form-data" style="display:none;">
        @csrf
        <input type="hidden" name="action" value="edit_custom_flower">
        <input type="hidden" name="id" id="flower-edit-id">
        <input type="hidden" name="display_name" id="flower-edit-display-name">
        <input type="hidden" name="name" id="flower-edit-name">
        <input type="hidden" name="price" id="flower-edit-price">
        <input type="hidden" name="stock_quantity" id="flower-edit-stock">
        <input type="hidden" name="sort_order" id="flower-edit-sort">
        <input type="hidden" name="is_active" id="flower-edit-active" value="1">
        <input type="file" name="image" id="flower-edit-image" class="photo-file-input" accept="image/*">
    </form>

    <form id="flowerDeleteForm" action="{{ route('admin.dashboard.post') }}" method="POST" style="display:none;">
        @csrf
        <input type="hidden" name="action" value="delete_custom_flower">
        <input type="hidden" name="id" id="flower-delete-id">
    </form>

    {{-- Shared hidden forms used by the inline-edit tables --}}
    <form id="variantEditForm" action="{{ route('admin.dashboard.post') }}" method="POST" enctype="multipart/form-data" style="display:none;">
        @csrf
        <input type="hidden" name="action" value="edit_variant">
        <input type="hidden" name="id">
        <input type="hidden" name="flower_id">
        <input type="hidden" name="variant_type">
        <input type="hidden" name="display_name">
        <input type="hidden" name="price">
        <input type="hidden" name="hex_color">
        <input type="hidden" name="sort_order">
        <input type="hidden" name="is_active" value="1">
        <input type="file" name="image" id="variant-edit-image" class="photo-file-input" accept="image/*">
    </form>
    <form id="variantDeleteForm" action="{{ route('admin.dashboard.post') }}" method="POST" style="display:none;">
        @csrf
        <input type="hidden" name="action" value="delete_variant">
        <input type="hidden" name="id">
    </form>

    <form id="fillerEditForm" action="{{ route('admin.dashboard.post') }}" method="POST" enctype="multipart/form-data" style="display:none;">
        @csrf
        <input type="hidden" name="action" value="edit_filler">
        <input type="hidden" name="id">
        <input type="hidden" name="display_name">
        <input type="hidden" name="name">
        <input type="hidden" name="price">
        <input type="hidden" name="sort_order">
        <input type="hidden" name="is_active" value="1">
        <input type="file" name="image" id="filler-edit-image" class="photo-file-input" accept="image/*">
    </form>
    <form id="fillerDeleteForm" action="{{ route('admin.dashboard.post') }}" method="POST" style="display:none;">
        @csrf
        <input type="hidden" name="action" value="delete_filler">
        <input type="hidden" name="id">
    </form>

    <form id="colorEditForm" action="{{ route('admin.dashboard.post') }}" method="POST" enctype="multipart/form-data" style="display:none;">
        @csrf
        <input type="hidden" name="action" value="edit_custom_color">
        <input type="hidden" name="id">
        <input type="hidden" name="display_name">
        <input type="hidden" name="name">
        <input type="hidden" name="price">
        <input type="hidden" name="hex_color">
        <input type="hidden" name="sort_order">
        <input type="hidden" name="is_active" value="1">
        <input type="hidden" name="clear_image" id="color-edit-clear" value="">
        <input type="file" name="image" id="color-edit-image" class="photo-file-input" accept="image/*">
    </form>
    <form id="colorDeleteForm" action="{{ route('admin.dashboard.post') }}" method="POST" style="display:none;">
        @csrf
        <input type="hidden" name="action" value="delete_custom_color">
        <input type="hidden" name="id">
    </form>

    <form id="ribbonEditForm" action="{{ route('admin.dashboard.post') }}" method="POST" enctype="multipart/form-data" style="display:none;">
        @csrf
        <input type="hidden" name="action" value="edit_ribbon">
        <input type="hidden" name="id">
        <input type="hidden" name="display_name">
        <input type="hidden" name="name">
        <input type="hidden" name="price">
        <input type="hidden" name="sort_order">
        <input type="hidden" name="is_active" value="1">
        <input type="hidden" name="clear_image" id="ribbon-edit-clear" value="">
        <input type="file" name="image" id="ribbon-edit-image" class="photo-file-input" accept="image/*">
    </form>
    <form id="ribbonDeleteForm" action="{{ route('admin.dashboard.post') }}" method="POST" style="display:none;">
        @csrf
        <input type="hidden" name="action" value="delete_ribbon">
        <input type="hidden" name="id">
    </form>

    <form id="styleEditForm" action="{{ route('admin.dashboard.post') }}" method="POST" enctype="multipart/form-data" style="display:none;">
        @csrf
        <input type="hidden" name="action" value="edit_custom_style">
        <input type="hidden" name="id">
        <input type="hidden" name="display_name">
        <input type="hidden" name="name">
        <input type="hidden" name="price">
        <input type="hidden" name="sort_order">
        <input type="hidden" name="is_active" value="1">
        <input type="file" name="image" id="style-edit-image" class="photo-file-input" accept="image/*">
    </form>
    <form id="styleDeleteForm" action="{{ route('admin.dashboard.post') }}" method="POST" style="display:none;">
        @csrf
        <input type="hidden" name="action" value="delete_custom_style">
        <input type="hidden" name="id">
    </form>

    {{-- Generic photo lightbox: click thumbnail to enlarge, then replace or remove --}}
    <div class="edit-modal" id="photoLightbox">
        <div class="edit-modal-box">
            <h3 style="color:var(--secondary);margin-bottom:16px;">Photo</h3>
            <div style="text-align:center;">
                <img id="photoLightboxImg" src="" alt="" style="max-width:100%;max-height:55vh;border-radius:10px;display:none;">
                <div id="photoLightboxSwatch" class="lightbox-swatch" style="display:none;"></div>
                <p id="photoLightboxNoImg" style="color:#8a8a8a;padding:30px;display:none;"><i class="fas fa-image"></i> No photo uploaded yet.</p>
            </div>
            <div style="text-align:center;margin-top:14px;">
                <button type="button" class="btn-sm btn-edit" id="photoLightboxReplace"><i class="fas fa-upload"></i> Replace Photo</button>
                <button type="button" class="btn-sm btn-del" id="photoLightboxRemove" style="display:none;"><i class="fas fa-trash"></i> Remove Photo</button>
            </div>
            <p id="photoLightboxFile" style="text-align:center;font-size:0.8rem;color:var(--secondary);margin-top:8px;display:none;"></p>
            <div style="display:flex;gap:10px;justify-content:center;margin-top:14px;">
                <button type="button" class="btn-sm btn-del" onclick="document.getElementById('photoLightbox').classList.remove('show');">Close</button>
            </div>
        </div>
    </div>

    <div class="edit-modal" id="editServicePhotoModal">
        <div class="edit-modal-box">
            <h3 style="color:var(--secondary);margin-bottom:16px;">Edit Service Photo</h3>
            <form action="{{ route('admin.dashboard.post') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="action" value="edit_service_photo">
                <input type="hidden" name="id" id="edit-service-photo-id">
                <div class="form-grid">
                    <div>
                        <label>Category</label>
                        <select name="category" id="edit-service-photo-category" required>
                            @foreach ($serviceCategories as $cat)
                                <option value="{{ $cat }}">{{ $serviceNames[$cat] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div><label>Replace Photo</label><input type="file" name="image" accept="image/*"></div>
                    <div><label>Caption</label><input type="text" name="caption" id="edit-service-photo-caption"></div>
                </div>
                <div style="display:flex;gap:10px;margin-top:14px;">
                    <button type="submit" class="btn-sm btn-ok">Save Changes</button>
                    <button type="button" class="btn-sm btn-del" onclick="document.getElementById('editServicePhotoModal').classList.remove('show');">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function initAdmin() {
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
                this.classList.add('active');
                document.getElementById('tab-' + this.dataset.tab).classList.add('active');
                const url = new URL(window.location.href);
                url.searchParams.set('tab', this.dataset.tab);
                window.history.replaceState({}, '', url.toString());
            });
        });

        document.querySelectorAll('.edit-product-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('edit-id').value = this.dataset.id;
                document.getElementById('edit-name').value = this.dataset.name;
                document.getElementById('edit-price').value = this.dataset.price;
                document.getElementById('edit-categories').value = this.dataset.categories.split(',');
                document.getElementById('edit-flowers').value = this.dataset.flowers ? this.dataset.flowers.split(',') : [];
                document.getElementById('edit-active').value = this.dataset.active || '1';
                document.getElementById('edit-image').value = this.dataset.image;
                document.getElementById('edit-description').value = this.dataset.description;
                document.getElementById('editProductModal').classList.add('show');
            });
        });

        function markRowDirty(row) {
            row.classList.add('dirty');
        }

        function bindEditRow(selector) {
            document.querySelectorAll(selector).forEach(row => {
                const editForm = document.getElementById(row.dataset.form);
                if (!editForm) return;
                row.querySelectorAll('[data-field], .active-check').forEach(el => {
                    el.addEventListener('input', () => markRowDirty(row));
                    el.addEventListener('change', () => markRowDirty(row));
                });
                row.querySelector('.save-row-btn').addEventListener('click', function() {
                    editForm.querySelector('[name="id"]').value = row.dataset.id;
                    ['display_name', 'name', 'price', 'sort_order', 'stock_quantity', 'hex_color'].forEach(field => {
                        const src = row.querySelector('[data-field="' + field + '"]');
                        const dst = editForm.querySelector('[name="' + field + '"]');
                        if (src && dst) dst.value = src.value;
                    });
                    const activeCheck = row.querySelector('.active-check');
                    const activeDst = editForm.querySelector('[name="is_active"]');
                    if (activeCheck && activeDst) activeDst.value = activeCheck.checked ? '1' : '0';
                    const parentDst = editForm.querySelector('[name="flower_id"]');
                    if (row.dataset.parentId && parentDst) parentDst.value = row.dataset.parentId;
                    const typeDst = editForm.querySelector('[name="variant_type"]');
                    if (row.dataset.type && typeDst) typeDst.value = row.dataset.type;
                    saveDashboardState();
                    editForm.submit();
                });
                row.querySelector('.delete-row-btn').addEventListener('click', function() {
                    if (!confirm('Delete this item?')) return;
                    const deleteForm = document.getElementById(row.dataset.deleteForm);
                    deleteForm.querySelector('[name="id"]').value = row.dataset.id;
                    saveDashboardState();
                    deleteForm.submit();
                });
            });
        }

        bindEditRow('.flower-edit-row');
        bindEditRow('.variant-edit-row');
        bindEditRow('.filler-edit-row');
        bindEditRow('.color-edit-row');
        bindEditRow('.ribbon-edit-row');
        bindEditRow('.style-edit-row');

        let activePhotoInputId = null;
        let activeClearInputId = null;
        let activeEditRow = null;
        const photoLightboxEl = document.getElementById('photoLightbox');
        const photoLightboxImg = document.getElementById('photoLightboxImg');
        const photoLightboxNoImg = document.getElementById('photoLightboxNoImg');
        const photoLightboxSwatch = document.getElementById('photoLightboxSwatch');
        const photoLightboxFile = document.getElementById('photoLightboxFile');
        const photoLightboxReplace = document.getElementById('photoLightboxReplace');
        const photoLightboxRemove = document.getElementById('photoLightboxRemove');

        document.querySelectorAll('.photo-thumb').forEach(thumb => {
            thumb.addEventListener('click', function() {
                activePhotoInputId = this.dataset.input || null;
                activeClearInputId = this.dataset.clearInput || null;
                activeEditRow = this.closest('.edit-row') || null;
                const fileInput = activePhotoInputId ? document.getElementById(activePhotoInputId) : null;
                if (fileInput) fileInput.value = '';
                photoLightboxFile.style.display = 'none';
                photoLightboxRemove.style.display = activeClearInputId ? 'inline-block' : 'none';
                photoLightboxSwatch.style.display = 'none';
                if (this.tagName === 'IMG' && this.src) {
                    photoLightboxImg.src = this.src;
                    photoLightboxImg.style.display = 'block';
                    photoLightboxNoImg.style.display = 'none';
                } else if (this.dataset.hex) {
                    photoLightboxImg.style.display = 'none';
                    photoLightboxNoImg.style.display = 'none';
                    photoLightboxSwatch.style.background = this.dataset.hex;
                    photoLightboxSwatch.style.display = 'block';
                } else {
                    photoLightboxImg.style.display = 'none';
                    photoLightboxNoImg.style.display = 'block';
                }
                photoLightboxEl.classList.add('show');
            });
        });

        photoLightboxReplace.addEventListener('click', function() {
            if (activePhotoInputId) document.getElementById(activePhotoInputId).click();
        });

        photoLightboxRemove.addEventListener('click', function() {
            if (activeClearInputId) {
                document.getElementById(activeClearInputId).value = '1';
                if (activeEditRow) markRowDirty(activeEditRow);
                photoLightboxFile.textContent = 'Current photo will be removed when you click Save.';
                photoLightboxFile.style.display = 'block';
            }
        });

        document.querySelectorAll('.photo-file-input').forEach(input => {
            input.addEventListener('change', function() {
                const file = this.files[0];
                if (!file) return;
                if (activeEditRow) markRowDirty(activeEditRow);
                const reader = new FileReader();
                reader.onload = function(e) {
                    photoLightboxImg.src = e.target.result;
                    photoLightboxImg.style.display = 'block';
                    photoLightboxNoImg.style.display = 'none';
                    photoLightboxSwatch.style.display = 'none';
                    photoLightboxFile.textContent = 'New photo selected: ' + file.name + ' — click Save on that row to apply.';
                    photoLightboxFile.style.display = 'block';
                };
                reader.readAsDataURL(file);
            });
        });

        document.querySelectorAll('.edit-service-photo-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('edit-service-photo-id').value = this.dataset.id;
                document.getElementById('edit-service-photo-caption').value = this.dataset.caption || '';
                document.getElementById('edit-service-photo-category').value = this.dataset.category;
                document.getElementById('editServicePhotoModal').classList.add('show');
            });
        });

        document.querySelectorAll('.variant-type-select').forEach(sel => {
            const toggleHex = () => {
                const hexField = sel.closest('form').querySelector('.variant-hex-field');
                if (hexField) hexField.style.display = sel.value === 'color' ? '' : 'none';
            };
            sel.addEventListener('change', toggleHex);
            toggleHex();
        });

        document.querySelectorAll('.card.collapsible h3').forEach(h3 => {
            h3.addEventListener('click', () => {
                const card = h3.parentElement;
                const wasCollapsed = card.classList.contains('collapsed');
                card.closest('.tab-panel').querySelectorAll('.card.collapsible').forEach(other => {
                    if (other !== card) other.classList.add('collapsed');
                });
                card.classList.toggle('collapsed', !wasCollapsed);
            });
        });
        }

        function saveDashboardState() {
            const openCardEl = document.querySelector('.card.collapsible:not(.collapsed)');
            const ref = openCardEl ? {
                panel: openCardEl.closest('.tab-panel') ? openCardEl.closest('.tab-panel').id : '',
                index: Array.prototype.indexOf.call(openCardEl.parentElement.querySelectorAll(':scope > .card.collapsible'), openCardEl)
            } : null;
            sessionStorage.setItem('hs_scroll', String(window.scrollY));
            sessionStorage.setItem('hs_open_card', ref ? JSON.stringify(ref) : '');
        }

        function restoreDashboardState() {
            const savedScroll = sessionStorage.getItem('hs_scroll');
            if (savedScroll) window.scrollTo(0, parseInt(savedScroll, 10));
            const refJson = sessionStorage.getItem('hs_open_card');
            if (refJson) {
                try {
                    const ref = JSON.parse(refJson);
                    const panel = document.getElementById(ref.panel);
                    if (panel) {
                        const cards = panel.querySelectorAll(':scope > .card.collapsible');
                        if (cards[ref.index]) cards[ref.index].classList.remove('collapsed');
                    }
                } catch (e) {}
            }
            sessionStorage.removeItem('hs_scroll');
            sessionStorage.removeItem('hs_open_card');
        }

        document.addEventListener('submit', saveDashboardState, true);
        initAdmin();
        restoreDashboardState();
    </script></body>
</html>
