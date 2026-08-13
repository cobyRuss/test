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
        .admin-nav { width: 210px; background: #fff; box-shadow: 2px 0 10px rgba(0,0,0,0.05); padding: 20px 0; flex-shrink: 0; }
        .admin-nav button { display: block; width: 100%; text-align: left; padding: 12px 24px; background: none; border: none; cursor: pointer; font-size: 0.95rem; color: #5a4a4a; font-weight: 500; border-left: 4px solid transparent; }
        .admin-nav button:hover, .admin-nav button.active { background: #f9f3f4; color: #d17b88; border-left-color: #d17b88; }
        .admin-content { flex: 1; padding: 30px; overflow-x: auto; }
        .tab-panel { display: none; }
        .tab-panel.active { display: block; }
        .card { background: #fff; border-radius: 10px; padding: 22px; box-shadow: 0 4px 15px rgba(0,0,0,0.06); margin-bottom: 22px; }
        .card h3 { color: #8a9b6e; margin: 0 0 16px; }
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
        .btn-verify { background: #1a4a8a; }
        .btn-warn { background: #b37400; }
        .filter-bar { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 16px; align-items: center; }
        .filter-bar input, .filter-bar select { padding: 8px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 0.85rem; }
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-size: 0.92rem; }
        .alert-success { background: #e8f5e9; color: #1e7a2c; border: 1px solid #b7e0bd; }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; margin-bottom: 12px; }
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
        @media (max-width: 900px) { .admin-body { flex-direction: column; } .admin-nav { width: 100%; display: flex; overflow-x: auto; padding: 8px 0; } .admin-nav button { border-left: none; border-bottom: 3px solid transparent; width: auto; padding: 10px 16px; white-space: nowrap; } .admin-nav button.active { border-bottom-color: #d17b88; } }
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
                <div class="card">
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

                <div class="card">
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
                        <input type="text" name="cat_name" placeholder="Category slug (e.g. orchids)" required>
                        <input type="text" name="cat_display" placeholder="Display name (e.g. Orchids)" required>
                        <button type="submit" class="btn-sm btn-ok">Add Category</button>
                    </form>
                </div>

                <div class="card">
                    <h3>Product Categories</h3>
                    <table class="admin-table">
                        <thead><tr><th>Slug</th><th>Display Name</th><th>Products</th><th>Actions</th></tr></thead>
                        <tbody>
                            @forelse ($categoriesList as $category)
                                <tr>
                                    <td>{{ $category->slug }}</td>
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
                <div class="card">
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
                    <div class="card">
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
                <div class="card">
                    <h3>Add Flower</h3>
                    <form action="{{ route('admin.dashboard.post') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="action" value="add_custom_flower">
                        <div class="form-grid">
                            <div><label>Name (slug)</label><input type="text" name="name" placeholder="e.g. rose" required></div>
                            <div><label>Display Name</label><input type="text" name="display_name" placeholder="e.g. Roses"></div>
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
                </div>

                <div class="card">
                    <h3>Flowers ({{ $customFlowers->count() }})</h3>
                    <table class="admin-table">
                        <thead><tr><th></th><th>Display Name</th><th>Slug</th><th>Price</th><th>Stock</th><th>Sort</th><th>Active</th><th>Actions</th></tr></thead>
                        <tbody>
                            @forelse ($customFlowers as $flower)
                                <tr>
                                    <td>
                                        @if ($flower->image_url)
                                            <img src="{{ asset('images/'.$flower->image_url) }}" alt="">
                                        @else
                                            <i class="fas fa-seedling" style="font-size:1.4rem;color:#8a9b6e;"></i>
                                        @endif
                                    </td>
                                    <td><strong>{{ $flower->display_name }}</strong></td>
                                    <td>{{ $flower->name }}</td>
                                    <td>₱{{ number_format($flower->price, 2) }}</td>
                                    <td>
                                        @if ((int) $flower->stock_quantity > 0)
                                            {{ $flower->stock_quantity }}
                                        @else
                                            <span style="display:inline-block;background:#fdecea;color:#c0392b;border-radius:20px;padding:2px 10px;font-size:0.75rem;">Out of stock</span>
                                        @endif
                                    </td>
                                    <td>{{ $flower->sort_order }}</td>
                                    <td>{{ $flower->is_active ? 'Yes' : 'No' }}</td>
                                    <td>
                                        <button class="btn-sm btn-edit edit-flower-btn"
                                                data-id="{{ $flower->id }}"
                                                data-name="{{ $flower->name }}"
                                                data-display-name="{{ $flower->display_name }}"
                                                data-price="{{ $flower->price }}"
                                                data-stock="{{ $flower->stock_quantity }}"
                                                data-sort-order="{{ $flower->sort_order }}"
                                                data-active="{{ $flower->is_active ? '1' : '0' }}">
                                            Edit
                                        </button>
                                        <form action="{{ route('admin.dashboard.post') }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="action" value="delete_custom_flower">
                                            <input type="hidden" name="id" value="{{ $flower->id }}">
                                            <button type="submit" class="btn-sm btn-del" onclick="return confirm('Delete this flower?');">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="empty-row">No flowers yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card">
                    <h3>Flower Variants (Sizes / Colors)</h3>
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
                        <thead><tr><th>Flower</th><th>Type</th><th>Name</th><th>Price</th><th>Color / Image</th><th>Active</th><th>Actions</th></tr></thead>
                        <tbody>
                            @forelse ($customFlowers as $flower)
                                @forelse ($flower->variants as $variant)
                                    <tr>
                                        <td>{{ $flower->display_name }}</td>
                                        <td>{{ ucfirst($variant->variant_type) }}</td>
                                        <td>{{ $variant->display_name }}</td>
                                        <td>₱{{ number_format($variant->price, 2) }}</td>
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
                                            @if ($variant->is_active)
                                                <span class="badge badge-delivered">Active</span>
                                            @else
                                                <span class="badge badge-cancelled">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn-sm btn-edit edit-variant-btn"
                                                    data-id="{{ $variant->id }}"
                                                    data-flower-id="{{ $flower->id }}"
                                                    data-type="{{ $variant->variant_type }}"
                                                    data-name="{{ $variant->display_name }}"
                                                    data-price="{{ $variant->price }}"
                                                    data-hex="{{ $variant->hex_color }}"
                                                    data-active="{{ $variant->is_active ? '1' : '0' }}"
                                                    data-sort="{{ $variant->sort_order }}">
                                                Edit
                                            </button>
                                            <form action="{{ route('admin.dashboard.post') }}" method="POST" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="action" value="delete_variant">
                                                <input type="hidden" name="id" value="{{ $variant->id }}">
                                                <button type="submit" class="btn-sm btn-del" onclick="return confirm('Delete this variant?');">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td>{{ $flower->display_name }}</td>
                                        <td colspan="6" class="empty-row" style="padding:12px 0;">No variants yet.</td>
                                    </tr>
                                @endforelse
                            @empty
                                <tr><td colspan="7" class="empty-row">Add flowers first.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card">
                    <h3>Fillers</h3>
                    <p style="font-size:0.8rem;color:#8a8a8a;margin:-8px 0 14px;">Customers can pick each filler only once (but may pick several different fillers).</p>
                    <form action="{{ route('admin.dashboard.post') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="action" value="add_filler">
                        <div class="form-grid">
                            <div><label>Name (slug)</label><input type="text" name="name" placeholder="e.g. eucalyptus" required></div>
                            <div><label>Display Name</label><input type="text" name="display_name" placeholder="e.g. Eucalyptus"></div>
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
                        <thead><tr><th></th><th>Display Name</th><th>Slug</th><th>Price</th><th>Sort</th><th>Active</th><th>Actions</th></tr></thead>
                        <tbody>
                            @forelse ($customFillers as $filler)
                                <tr>
                                    <td>
                                        @if ($filler->image_url)
                                            <img src="{{ asset('images/'.$filler->image_url) }}" alt="">
                                        @else
                                            <i class="fas fa-leaf" style="font-size:1.4rem;color:var(--secondary);"></i>
                                        @endif
                                    </td>
                                    <td><strong>{{ $filler->display_name }}</strong></td>
                                    <td>{{ $filler->name }}</td>
                                    <td>₱{{ number_format($filler->price, 2) }}</td>
                                    <td>{{ $filler->sort_order }}</td>
                                    <td>{{ $filler->is_active ? 'Yes' : 'No' }}</td>
                                    <td>
                                        <button class="btn-sm btn-edit edit-filler-btn"
                                                data-id="{{ $filler->id }}"
                                                data-name="{{ $filler->name }}"
                                                data-display-name="{{ $filler->display_name }}"
                                                data-price="{{ $filler->price }}"
                                                data-sort-order="{{ $filler->sort_order }}"
                                                data-active="{{ $filler->is_active ? '1' : '0' }}">
                                            Edit
                                        </button>
                                        <form action="{{ route('admin.dashboard.post') }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="action" value="delete_filler">
                                            <input type="hidden" name="id" value="{{ $filler->id }}">
                                            <button type="submit" class="btn-sm btn-del" onclick="return confirm('Delete this filler?');">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="empty-row">No fillers yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card">
                    <h3>Add Wrapper Color</h3>
                    <form action="{{ route('admin.dashboard.post') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="action" value="add_custom_color">
                        <div class="form-grid">
                            <div><label>Name (slug)</label><input type="text" name="name" placeholder="e.g. red" required></div>
                            <div><label>Display Name</label><input type="text" name="display_name" placeholder="e.g. Red"></div>
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
                </div>

                <div class="card">
                    <h3>Wrapper Colors ({{ $customColors->count() }})</h3>
                    <table class="admin-table">
                        <thead><tr><th></th><th>Display Name</th><th>Slug</th><th>Price</th><th>Sort</th><th>Active</th><th>Actions</th></tr></thead>
                        <tbody>
                            @forelse ($customColors as $color)
                                <tr>
                                    <td>
                                        @php
                                            $swatchMap = ['red' => '#e74c3c', 'pink' => '#e8b4bc', 'white' => '#f9f3f4', 'yellow' => '#f1c40f', 'purple' => '#9b59b6'];
                                            $swatchBg = $color->hex_color ?: ($swatchMap[$color->name] ?? 'linear-gradient(45deg,#e74c3c,#e8b4bc,#f1c40f,#9b59b6)');
                                        @endphp
                                        @if ($color->image_url)
                                            <img src="{{ asset('images/'.$color->image_url) }}" alt="" style="width:40px;height:40px;border-radius:50%;object-fit:cover;border:2px solid #ddd;">
                                        @else
                                            <div style="width:40px;height:40px;border-radius:50%;background:{{ $swatchBg }};border:2px solid #ddd;"></div>
                                        @endif
                                    </td>
                                    <td><strong>{{ $color->display_name }}</strong></td>
                                    <td>{{ $color->name }}</td>
                                    <td>₱{{ number_format($color->price, 2) }}</td>
                                    <td>{{ $color->sort_order }}</td>
                                    <td>{{ $color->is_active ? 'Yes' : 'No' }}</td>
                                    <td>
                                        <button class="btn-sm btn-edit edit-color-btn"
                                                data-id="{{ $color->id }}"
                                                data-name="{{ $color->name }}"
                                                data-display-name="{{ $color->display_name }}"
                                                data-price="{{ $color->price }}"
                                                data-hex-color="{{ $color->hex_color }}"
                                                data-image="{{ $color->image_url }}"
                                                data-sort-order="{{ $color->sort_order }}"
                                                data-active="{{ $color->is_active ? '1' : '0' }}">
                                            Edit
                                        </button>
                                        <form action="{{ route('admin.dashboard.post') }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="action" value="delete_custom_color">
                                            <input type="hidden" name="id" value="{{ $color->id }}">
                                            <button type="submit" class="btn-sm btn-del" onclick="return confirm('Delete this wrapper color?');">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="empty-row">No wrapper colors yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card">
                    <h3>Add Ribbon</h3>
                    <form action="{{ route('admin.dashboard.post') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="action" value="add_ribbon">
                        <div class="form-grid">
                            <div><label>Name (slug)</label><input type="text" name="name" placeholder="e.g. satin_ribbon" required></div>
                            <div><label>Display Name</label><input type="text" name="display_name" placeholder="e.g. Satin Ribbon"></div>
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
                </div>

                <div class="card">
                    <h3>Ribbons ({{ $customRibbons->count() }})</h3>
                    <table class="admin-table">
                        <thead><tr><th></th><th>Display Name</th><th>Slug</th><th>Price</th><th>Sort</th><th>Active</th><th>Actions</th></tr></thead>
                        <tbody>
                            @forelse ($customRibbons as $ribbon)
                                <tr>
                                    <td>
                                        @if ($ribbon->image_url)
                                            <img src="{{ asset('images/'.$ribbon->image_url) }}" alt="" style="width:40px;height:40px;border-radius:8px;object-fit:cover;border:2px solid #ddd;">
                                        @else
                                            <i class="fas fa-ribbon" style="font-size:1.4rem;color:var(--secondary);"></i>
                                        @endif
                                    </td>
                                    <td><strong>{{ $ribbon->display_name }}</strong></td>
                                    <td>{{ $ribbon->name }}</td>
                                    <td>₱{{ number_format($ribbon->price, 2) }}</td>
                                    <td>{{ $ribbon->sort_order }}</td>
                                    <td>{{ $ribbon->is_active ? 'Yes' : 'No' }}</td>
                                    <td>
                                        <button class="btn-sm btn-edit edit-ribbon-btn"
                                                data-id="{{ $ribbon->id }}"
                                                data-name="{{ $ribbon->name }}"
                                                data-display-name="{{ $ribbon->display_name }}"
                                                data-price="{{ $ribbon->price }}"
                                                data-image="{{ $ribbon->image_url }}"
                                                data-sort-order="{{ $ribbon->sort_order }}"
                                                data-active="{{ $ribbon->is_active ? '1' : '0' }}">
                                            Edit
                                        </button>
                                        <form action="{{ route('admin.dashboard.post') }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="action" value="delete_ribbon">
                                            <input type="hidden" name="id" value="{{ $ribbon->id }}">
                                            <button type="submit" class="btn-sm btn-del" onclick="return confirm('Delete this ribbon and all its color/size variants?');">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="empty-row">No ribbons yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card">
                    <h3>Ribbon Variants (Colors / Sizes)</h3>
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
                        <thead><tr><th>Ribbon</th><th>Type</th><th>Name</th><th>Price</th><th>Color / Image</th><th>Active</th><th>Actions</th></tr></thead>
                        <tbody>
                            @forelse ($customRibbons as $ribbon)
                                @forelse ($ribbon->variants as $variant)
                                    <tr>
                                        <td>{{ $ribbon->display_name }}</td>
                                        <td>{{ ucfirst($variant->variant_type) }}</td>
                                        <td>{{ $variant->display_name }}</td>
                                        <td>₱{{ number_format($variant->price, 2) }}</td>
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
                                            @if ($variant->is_active)
                                                <span class="badge badge-delivered">Active</span>
                                            @else
                                                <span class="badge badge-cancelled">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn-sm btn-edit edit-variant-btn"
                                                    data-id="{{ $variant->id }}"
                                                    data-flower-id="{{ $ribbon->id }}"
                                                    data-type="{{ $variant->variant_type }}"
                                                    data-name="{{ $variant->display_name }}"
                                                    data-price="{{ $variant->price }}"
                                                    data-hex="{{ $variant->hex_color }}"
                                                    data-active="{{ $variant->is_active ? '1' : '0' }}"
                                                    data-sort="{{ $variant->sort_order }}">
                                                Edit
                                            </button>
                                            <form action="{{ route('admin.dashboard.post') }}" method="POST" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="action" value="delete_variant">
                                                <input type="hidden" name="id" value="{{ $variant->id }}">
                                                <button type="submit" class="btn-sm btn-del" onclick="return confirm('Delete this variant?');">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td>{{ $ribbon->display_name }}</td>
                                        <td colspan="6" class="empty-row" style="padding:12px 0;">No variants yet.</td>
                                    </tr>
                                @endforelse
                            @empty
                                <tr><td colspan="7" class="empty-row">Add ribbons first.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card">
                    <h3>Add Style</h3>
                    <form action="{{ route('admin.dashboard.post') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="action" value="add_custom_style">
                        <div class="form-grid">
                            <div><label>Name (slug)</label><input type="text" name="name" placeholder="e.g. bouquet" required></div>
                            <div><label>Display Name</label><input type="text" name="display_name" placeholder="e.g. Hand-Tied Bouquet"></div>
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
                </div>

                <div class="card">
                    <h3>Styles ({{ $customStyles->count() }})</h3>
                    <table class="admin-table">
                        <thead><tr><th></th><th>Display Name</th><th>Slug</th><th>Price</th><th>Sort</th><th>Active</th><th>Actions</th></tr></thead>
                        <tbody>
                            @forelse ($customStyles as $style)
                                <tr>
                                    <td>
                                        @if ($style->image_url)
                                            <img src="{{ asset('images/'.$style->image_url) }}" alt="">
                                        @else
                                            <i class="fas fa-seedling" style="font-size:1.4rem;color:#8a9b6e;"></i>
                                        @endif
                                    </td>
                                    <td><strong>{{ $style->display_name }}</strong></td>
                                    <td>{{ $style->name }}</td>
                                    <td>₱{{ number_format($style->price, 2) }}</td>
                                    <td>{{ $style->sort_order }}</td>
                                    <td>{{ $style->is_active ? 'Yes' : 'No' }}</td>
                                    <td>
                                        <button class="btn-sm btn-edit edit-style-btn"
                                                data-id="{{ $style->id }}"
                                                data-name="{{ $style->name }}"
                                                data-display-name="{{ $style->display_name }}"
                                                data-price="{{ $style->price }}"
                                                data-sort-order="{{ $style->sort_order }}"
                                                data-active="{{ $style->is_active ? '1' : '0' }}">
                                            Edit
                                        </button>
                                        <form action="{{ route('admin.dashboard.post') }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="action" value="delete_custom_style">
                                            <input type="hidden" name="id" value="{{ $style->id }}">
                                            <button type="submit" class="btn-sm btn-del" onclick="return confirm('Delete this style?');">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="empty-row">No styles yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
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
                                            <select name="new_status" onchange="this.form.submit()" style="padding:6px;border:1px solid #ddd;border-radius:6px;font-size:0.8rem;">
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

    <div class="edit-modal" id="editFlowerModal">
        <div class="edit-modal-box">
            <h3 style="color:var(--secondary);margin-bottom:16px;">Edit Flower</h3>
            <form action="{{ route('admin.dashboard.post') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="action" value="edit_custom_flower">
                <input type="hidden" name="id" id="edit-flower-id">
                <div class="form-grid">
                    <div><label>Name (slug)</label><input type="text" name="name" id="edit-flower-name" required></div>
                    <div><label>Display Name</label><input type="text" name="display_name" id="edit-flower-display-name"></div>
                    <div><label>Price (₱)</label><input type="number" step="0.01" min="0" name="price" id="edit-flower-price" required></div>
                    <div><label>Stock Qty (0 = out of stock)</label><input type="number" min="0" name="stock_quantity" id="edit-flower-stock" required></div>
                    <div><label>Sort Order</label><input type="number" min="0" name="sort_order" id="edit-flower-sort-order"></div>
                    <div><label>Replace Photo</label><input type="file" name="image" accept="image/*"></div>
                    <div>
                        <label>Active</label>
                        <select name="is_active" id="edit-flower-active">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
                <div style="display:flex;gap:10px;margin-top:14px;">
                    <button type="submit" class="btn-sm btn-ok">Save Changes</button>
                    <button type="button" class="btn-sm btn-del" onclick="document.getElementById('editFlowerModal').classList.remove('show');">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <div class="edit-modal" id="editColorModal">
        <div class="edit-modal-box">
            <h3 style="color:var(--secondary);margin-bottom:16px;">Edit Wrapper Color</h3>
            <form action="{{ route('admin.dashboard.post') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="action" value="edit_custom_color">
                <input type="hidden" name="id" id="edit-color-id">
                <div class="form-grid">
                    <div><label>Name (slug)</label><input type="text" name="name" id="edit-color-name" required></div>
                    <div><label>Display Name</label><input type="text" name="display_name" id="edit-color-display-name"></div>
                    <div><label>Price (₱)</label><input type="number" step="0.01" min="0" name="price" id="edit-color-price" required></div>
                    <div><label>Hex Color (e.g. #ff5733)</label><input type="text" name="hex_color" id="edit-color-hex" placeholder="#ff5733"></div>
                    <div><label>Replace Pattern Image (overrides hex)</label><input type="file" name="image" accept="image/*"></div>
                    <div><label>Sort Order</label><input type="number" min="0" name="sort_order" id="edit-color-sort-order"></div>
                    <div>
                        <label>Active</label>
                        <select name="is_active" id="edit-color-active">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <div>
                        <label><input type="checkbox" name="clear_image" value="1"> Remove current pattern image</label>
                    </div>
                </div>
                <div style="display:flex;gap:10px;margin-top:14px;">
                    <button type="submit" class="btn-sm btn-ok">Save Changes</button>
                    <button type="button" class="btn-sm btn-del" onclick="document.getElementById('editColorModal').classList.remove('show');">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <div class="edit-modal" id="editRibbonModal">
        <div class="edit-modal-box">
            <h3 style="color:var(--secondary);margin-bottom:16px;">Edit Ribbon</h3>
            <form action="{{ route('admin.dashboard.post') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="action" value="edit_ribbon">
                <input type="hidden" name="id" id="edit-ribbon-id">
                <div class="form-grid">
                    <div><label>Name (slug)</label><input type="text" name="name" id="edit-ribbon-name" required></div>
                    <div><label>Display Name</label><input type="text" name="display_name" id="edit-ribbon-display-name"></div>
                    <div><label>Price (₱)</label><input type="number" step="0.01" min="0" name="price" id="edit-ribbon-price" required></div>
                    <div><label>Replace Photo</label><input type="file" name="image" accept="image/*"></div>
                    <div><label>Sort Order</label><input type="number" min="0" name="sort_order" id="edit-ribbon-sort-order"></div>
                    <div>
                        <label>Active</label>
                        <select name="is_active" id="edit-ribbon-active">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
                <div style="display:flex;gap:10px;margin-top:14px;">
                    <button type="submit" class="btn-sm btn-ok">Save Changes</button>
                    <button type="button" class="btn-sm btn-del" onclick="document.getElementById('editRibbonModal').classList.remove('show');">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <div class="edit-modal" id="editStyleModal">
        <div class="edit-modal-box">
            <h3 style="color:var(--secondary);margin-bottom:16px;">Edit Style</h3>
            <form action="{{ route('admin.dashboard.post') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="action" value="edit_custom_style">
                <input type="hidden" name="id" id="edit-style-id">
                <div class="form-grid">
                    <div><label>Name (slug)</label><input type="text" name="name" id="edit-style-name" required></div>
                    <div><label>Display Name</label><input type="text" name="display_name" id="edit-style-display-name"></div>
                    <div><label>Price (₱)</label><input type="number" step="0.01" min="0" name="price" id="edit-style-price" required></div>
                    <div><label>Sort Order</label><input type="number" min="0" name="sort_order" id="edit-style-sort-order"></div>
                    <div><label>Replace Photo</label><input type="file" name="image" accept="image/*"></div>
                    <div>
                        <label>Active</label>
                        <select name="is_active" id="edit-style-active">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
                <div style="display:flex;gap:10px;margin-top:14px;">
                    <button type="submit" class="btn-sm btn-ok">Save Changes</button>
                    <button type="button" class="btn-sm btn-del" onclick="document.getElementById('editStyleModal').classList.remove('show');">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <div class="edit-modal" id="editVariantModal">
        <div class="edit-modal-box">
            <h3 style="color:var(--secondary);margin-bottom:16px;">Edit Variant</h3>
            <form action="{{ route('admin.dashboard.post') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="action" value="edit_variant">
                <input type="hidden" name="id" id="edit-variant-id">
                <div class="form-grid">
                    <div>
                        <label>Flower</label>
                        <select name="flower_id" id="edit-variant-flower" required>
                            @foreach ($customFlowers as $flower)
                                <option value="{{ $flower->id }}">{{ $flower->display_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label>Variant Type</label>
                        <select name="variant_type" id="edit-variant-type" required>
                            <option value="size">Size</option>
                            <option value="color">Color</option>
                        </select>
                    </div>
                    <div><label>Name</label><input type="text" name="display_name" id="edit-variant-name" required></div>
                    <div><label>Price (₱)</label><input type="number" step="0.01" min="0" name="price" id="edit-variant-price"></div>
                    <div><label>Hex Color (for colors)</label><input type="text" name="hex_color" id="edit-variant-hex" placeholder="#ff5733"></div>
                    <div><label>Replace Photo (sizes; or pattern image for colors)</label><input type="file" name="image" accept="image/*"></div>
                    <div><label>Sort Order</label><input type="number" min="0" name="sort_order" id="edit-variant-sort"></div>
                    <div>
                        <label>Active</label>
                        <select name="is_active" id="edit-variant-active">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
                <div style="display:flex;gap:10px;margin-top:14px;">
                    <button type="submit" class="btn-sm btn-ok">Save Changes</button>
                    <button type="button" class="btn-sm btn-del" onclick="document.getElementById('editVariantModal').classList.remove('show');">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <div class="edit-modal" id="editFillerModal">
        <div class="edit-modal-box">
            <h3 style="color:var(--secondary);margin-bottom:16px;">Edit Filler</h3>
            <form action="{{ route('admin.dashboard.post') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="action" value="edit_filler">
                <input type="hidden" name="id" id="edit-filler-id">
                <div class="form-grid">
                    <div><label>Name (slug)</label><input type="text" name="name" id="edit-filler-name" required></div>
                    <div><label>Display Name</label><input type="text" name="display_name" id="edit-filler-display-name"></div>
                    <div><label>Price (₱)</label><input type="number" step="0.01" min="0" name="price" id="edit-filler-price" required></div>
                    <div><label>Sort Order</label><input type="number" min="0" name="sort_order" id="edit-filler-sort-order"></div>
                    <div><label>Replace Photo</label><input type="file" name="image" accept="image/*"></div>
                    <div>
                        <label>Active</label>
                        <select name="is_active" id="edit-filler-active">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
                <div style="display:flex;gap:10px;margin-top:14px;">
                    <button type="submit" class="btn-sm btn-ok">Save Changes</button>
                    <button type="button" class="btn-sm btn-del" onclick="document.getElementById('editFillerModal').classList.remove('show');">Cancel</button>
                </div>
            </form>
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

        document.querySelectorAll('.edit-flower-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('edit-flower-id').value = this.dataset.id;
                document.getElementById('edit-flower-name').value = this.dataset.name;
                document.getElementById('edit-flower-display-name').value = this.dataset.displayName;
                document.getElementById('edit-flower-price').value = this.dataset.price;
                document.getElementById('edit-flower-stock').value = this.dataset.stock;
                document.getElementById('edit-flower-sort-order').value = this.dataset.sortOrder;
                document.getElementById('edit-flower-active').value = this.dataset.active;
                document.getElementById('editFlowerModal').classList.add('show');
            });
        });

        document.querySelectorAll('.edit-color-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('edit-color-id').value = this.dataset.id;
                document.getElementById('edit-color-name').value = this.dataset.name;
                document.getElementById('edit-color-display-name').value = this.dataset.displayName;
                document.getElementById('edit-color-price').value = this.dataset.price;
                document.getElementById('edit-color-hex').value = this.dataset.hexColor || '';
                document.getElementById('edit-color-sort-order').value = this.dataset.sortOrder;
                document.getElementById('edit-color-active').value = this.dataset.active;
                document.getElementById('editColorModal').classList.add('show');
            });
        });

        document.querySelectorAll('.edit-style-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('edit-style-id').value = this.dataset.id;
                document.getElementById('edit-style-name').value = this.dataset.name;
                document.getElementById('edit-style-display-name').value = this.dataset.displayName;
                document.getElementById('edit-style-price').value = this.dataset.price;
                document.getElementById('edit-style-sort-order').value = this.dataset.sortOrder;
                document.getElementById('edit-style-active').value = this.dataset.active;
                document.getElementById('editStyleModal').classList.add('show');
            });
        });

        document.querySelectorAll('.edit-ribbon-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('edit-ribbon-id').value = this.dataset.id;
                document.getElementById('edit-ribbon-name').value = this.dataset.name;
                document.getElementById('edit-ribbon-display-name').value = this.dataset.displayName;
                document.getElementById('edit-ribbon-price').value = this.dataset.price;
                document.getElementById('edit-ribbon-sort-order').value = this.dataset.sortOrder;
                document.getElementById('edit-ribbon-active').value = this.dataset.active;
                document.getElementById('editRibbonModal').classList.add('show');
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

        document.querySelectorAll('.edit-variant-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('edit-variant-id').value = this.dataset.id;
                document.getElementById('edit-variant-flower').value = this.dataset.flowerId;
                document.getElementById('edit-variant-type').value = this.dataset.type;
                document.getElementById('edit-variant-name').value = this.dataset.name;
                document.getElementById('edit-variant-price').value = this.dataset.price;
                document.getElementById('edit-variant-hex').value = this.dataset.hex || '';
                document.getElementById('edit-variant-active').value = this.dataset.active || '1';
                document.getElementById('edit-variant-sort').value = this.dataset.sort;
                document.getElementById('editVariantModal').classList.add('show');
            });
        });

        document.querySelectorAll('.edit-filler-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('edit-filler-id').value = this.dataset.id;
                document.getElementById('edit-filler-name').value = this.dataset.name;
                document.getElementById('edit-filler-display-name').value = this.dataset.displayName;
                document.getElementById('edit-filler-price').value = this.dataset.price;
                document.getElementById('edit-filler-sort-order').value = this.dataset.sortOrder;
                document.getElementById('edit-filler-active').value = this.dataset.active || '1';
                document.getElementById('editFillerModal').classList.add('show');
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
    </script>
</body>
</html>
