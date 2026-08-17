@extends('layouts.app')

@section('title', $product->name.' | HappyStem')

@section('content')
    <style>
        .review-stars { display: inline-flex; gap: 2px; cursor: pointer; font-size: 1.4rem; }
        .review-stars .star { color: #ddd; transition: color 0.15s; }
        .review-stars .star.filled { color: #f5a623; }
        .review-stars .star:hover { color: #f5a623; }
        .review-summary { display: flex; align-items: center; gap: 18px; margin-bottom: 24px; padding: 20px; background: var(--light); border-radius: 10px; }
        .review-summary .avg { font-size: 2.4rem; font-weight: 700; color: var(--accent); }
        .review-summary .stars-static { color: #f5a623; font-size: 1.1rem; }
        .review-summary .count { font-size: 0.85rem; color: var(--dark); opacity: 0.7; }
        .review-card { padding: 18px 0; border-bottom: 1px solid #f0ebea; }
        .review-card:last-child { border-bottom: none; }
        .review-header { display: flex; align-items: center; gap: 12px; margin-bottom: 6px; }
        .review-avatar { width: 38px; height: 38px; border-radius: 50%; background: var(--secondary); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem; flex-shrink: 0; }
        .review-meta strong { color: var(--dark); font-size: 0.9rem; }
        .review-meta .review-date { font-size: 0.75rem; color: #aaa; margin-left: 8px; }
        .review-stars-static { color: #f5a623; font-size: 0.85rem; margin-bottom: 4px; }
        .review-comment { color: var(--dark); font-size: 0.9rem; line-height: 1.5; }
        .review-photos { display: flex; gap: 8px; margin-top: 10px; flex-wrap: wrap; }
        .review-photos img { width: 80px; height: 80px; object-fit: cover; border-radius: 8px; cursor: zoom-in; border: 2px solid #f0ebea; }
        .review-form-card { background: var(--light); border-radius: 12px; padding: 24px; margin-bottom: 24px; }
        .review-form-card h4 { color: var(--secondary); margin: 0 0 14px; }
        .review-form-card textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 0.9rem; resize: vertical; min-height: 80px; font-family: inherit; }
        .review-form-card textarea:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 2px rgba(209,123,136,0.15); }
        .review-photos-input { margin-top: 10px; }
        .review-photos-input input[type="file"] { font-size: 0.85rem; }
        .review-photos-preview { display: flex; gap: 8px; margin-top: 8px; flex-wrap: wrap; }
        .review-photos-preview img { width: 60px; height: 60px; object-fit: cover; border-radius: 6px; border: 2px solid #f0ebea; }
        .review-form-actions { margin-top: 14px; display: flex; gap: 10px; }
        .review-lightbox { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.85); z-index: 200; align-items: center; justify-content: center; cursor: zoom-out; }
        .review-lightbox.show { display: flex; }
        .review-lightbox img { max-width: 90vw; max-height: 85vh; border-radius: 8px; box-shadow: 0 8px 40px rgba(0,0,0,0.5); }
        .review-lightbox-close { position: absolute; top: 20px; right: 24px; background: rgba(255,255,255,0.2); border: none; color: #fff; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; font-size: 1.2rem; }
        .review-edit-btn, .review-delete-btn { background: none; border: none; cursor: pointer; font-size: 0.78rem; font-weight: 600; padding: 0; }
        .review-edit-btn { color: var(--secondary); }
        .review-delete-btn { color: #c94a4a; }
        .review-your-label { display: inline-block; background: var(--secondary); color: #fff; font-size: 0.7rem; font-weight: 700; padding: 2px 8px; border-radius: 10px; margin-left: 8px; vertical-align: middle; }
        .order-item-select { padding: 8px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 0.85rem; width: 100%; margin-top: 6px; }
    </style>

    <section style="padding: 50px 0;">
        <div class="container">
            <nav style="margin-bottom:30px;font-size:0.9rem;">
                <a href="{{ route('home') }}" style="color:var(--accent);text-decoration:none;">Home</a>
                &raquo;
                <a href="{{ route('products.index') }}" style="color:var(--accent);text-decoration:none;">Shop</a>
                &raquo;
                <span>{{ $product->name }}</span>
            </nav>

            <div style="display:flex;gap:40px;flex-wrap:wrap;background:#fff;border-radius:12px;padding:30px;box-shadow:0 8px 25px rgba(0,0,0,0.08);">
                <div style="flex:1;min-width:300px;">
                    <div class="product-img {{ $product->is_available ? '' : 'is-unavailable' }}" style="height:auto;border-radius:10px;position:relative;">
                        <img src="{{ asset('images/'.$product->image_url) }}" alt="{{ $product->name }}" style="height:420px;">
                        @if (! $product->is_available)
                            <div class="stock-overlay">Not available at the moment</div>
                        @endif
                    </div>
                </div>
                <div style="flex:1;min-width:300px;">
                    <p style="color:var(--secondary);font-weight:600;text-transform:uppercase;font-size:0.85rem;">{{ $product->categories->pluck('display_name')->join(' & ') }}</p>
                    <h2 style="color:var(--dark);margin:10px 0;font-size:2rem;">{{ $product->name }}</h2>
                    <div style="display:flex;align-items:center;gap:14px;margin:15px 0;">
                        <span class="product-price" style="font-size:1.6rem;">₱{{ number_format($product->price, 2) }}</span>
                        @if ($product->review_count > 0)
                            <span style="color:#f5a623;font-size:0.95rem;">@for ($i = 1; $i <= 5; $i)<i class="fas fa-star{{ $i <= round($product->average_rating) ? '' : '-o' }}"></i>@endfor</span>
                            <span style="font-size:0.8rem;color:#aaa;">({{ $product->review_count }} {{ $product->review_count === 1 ? 'review' : 'reviews' }})</span>
                        @endif
                    </div>
                    <p style="color:var(--dark);margin-bottom:25px;">{{ $product->description }}</p>

                    <div class="product-actions" style="margin-bottom:20px;">
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
                            <span class="unavailable-btn"><i class="fas fa-exclamation-triangle"></i> Unavailable at the moment</span>
                        @endif
                    </div>

                    <div style="border-top:1px solid #eee;padding-top:15px;font-size:0.9rem;color:var(--dark);">
                        <p><i class="fas fa-truck" style="color:var(--secondary);"></i> Delivery available across Abra (delivery fee applies).</p>
                        <p style="margin-top:8px;"><i class="fas fa-credit-card" style="color:var(--secondary);"></i> Pay via GCash or Cash on Delivery.</p>
                        <p style="margin-top:8px;"><i class="fas fa-tag" style="color:var(--secondary);"></i> Freshly arranged on the day of delivery.</p>
                    </div>
                </div>
            </div>

            {{-- ─────────── REVIEWS ─────────── --}}
            <div style="background:#fff;border-radius:12px;padding:30px;box-shadow:0 8px 25px rgba(0,0,0,0.08);margin-top:40px;">
                <h3 class="section-title" style="margin-top:0;margin-bottom:20px;">Customer Reviews</h3>

                @if ($product->review_count > 0)
                    <div class="review-summary">
                        <div class="avg">{{ $product->average_rating }}</div>
                        <div>
                            <div class="stars-static">
                                @for ($i = 1; $i <= 5; $i)<i class="fas fa-star{{ $i <= round($product->average_rating) ? '' : '-o' }}"></i>@endfor
                            </div>
                            <div class="count">{{ $product->review_count }} {{ $product->review_count === 1 ? 'review' : 'reviews' }}</div>
                        </div>
                    </div>
                @endif

                @auth('web')
                    @if ($canReview)
                        <div class="review-form-card" id="reviewFormCard">
                            <h4><i class="fas fa-pen"></i> Write a Review</h4>
                            <form action="{{ route('reviews.store', $product->id) }}" method="POST" enctype="multipart/form-data" id="reviewForm">
                                @csrf
                                @if ($eligibleOrderItems->count() > 1)
                                    <label style="font-size:0.82rem;font-weight:600;color:var(--dark);">Which order is this for?</label>
                                    <select name="order_item_id" class="order-item-select" required>
                                        <option value="">Select an order...</option>
                                        @foreach ($eligibleOrderItems as $item)
                                            <option value="{{ $item->id }}">#{{ $item->id }} — {{ $item->product_name }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="hidden" name="order_item_id" value="{{ $eligibleOrderItems->first()->id }}">
                                @endif

                                <div style="margin-top:12px;">
                                    <label style="font-size:0.82rem;font-weight:600;color:var(--dark);">Your Rating *</label>
                                    <div class="review-stars" id="formStars">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star-o star" data-value="{{ $i }}"></i>
                                        @endfor
                                    </div>
                                    <input type="hidden" name="rating" id="ratingInput" value="{{ old('rating', '') }}" required>
                                </div>

                                <div style="margin-top:12px;">
                                    <label style="font-size:0.82rem;font-weight:600;color:var(--dark);">Your Review (optional)</label>
                                    <textarea name="comment" placeholder="Share your experience with this product...">{{ old('comment') }}</textarea>
                                </div>

                                <div class="review-photos-input">
                                    <label style="font-size:0.82rem;font-weight:600;color:var(--dark);">Add Photos (optional, max 5)</label>
                                    <input type="file" name="photos[]" id="reviewPhotos" multiple accept="image/*">
                                    <div class="review-photos-preview" id="photoPreview"></div>
                                </div>

                                @error('rating')
                                    <p style="color:#c94a4a;font-size:0.8rem;margin-top:6px;">{{ $message }}</p>
                                @enderror
                                @error('error')
                                    <p style="color:#c94a4a;font-size:0.8rem;margin-top:6px;">{{ $message }}</p>
                                @enderror

                                <div class="review-form-actions">
                                    <button type="submit" class="add-to-cart-btn" style="border:none;cursor:pointer;"><i class="fas fa-paper-plane"></i> Submit Review</button>
                                </div>
                            </form>
                        </div>
                    @elseif ($existingReview)
                        <div class="review-form-card" style="border-left:4px solid var(--secondary);">
                            <h4><i class="fas fa-check-circle"></i> Your Review</h4>
                            <div class="review-your-label">YOUR REVIEW</div>
                            <div class="review-stars-static" style="margin-top:8px;">
                                @for ($i = 1; $i <= 5; $i)<i class="fas fa-star{{ $i <= $existingReview->rating ? '' : '-o' }}"></i>@endfor
                            </div>
                            @if ($existingReview->comment)
                                <p class="review-comment" style="margin-top:6px;">{{ $existingReview->comment }}</p>
                            @endif
                            @if ($existingReview->photos->isNotEmpty())
                                <div class="review-photos">
                                    @foreach ($existingReview->photos as $photo)
                                        <img src="{{ asset('images/'.$photo->image_url) }}" alt="Review photo" class="review-thumb" onclick="openReviewLightbox('{{ asset('images/'.$photo->image_url) }}')">
                                    @endforeach
                                </div>
                            @endif
                            <div style="margin-top:10px;display:flex;gap:12px;">
                                <button type="button" class="review-edit-btn" onclick="toggleEditReview()"><i class="fas fa-edit"></i> Edit</button>
                                <form action="{{ route('reviews.destroy', $existingReview->id) }}" method="POST" onsubmit="return confirm('Delete your review?');" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="review-delete-btn"><i class="fas fa-trash"></i> Delete</button>
                                </form>
                            </div>

                            <div id="editReviewForm" style="display:none;margin-top:16px;padding-top:16px;border-top:1px dashed #eee;">
                                <form action="{{ route('reviews.update', $existingReview->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="rating" id="editRatingInput" value="{{ $existingReview->rating }}">
                                    <label style="font-size:0.82rem;font-weight:600;color:var(--dark);">Your Rating</label>
                                    <div class="review-stars" id="editStars">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star{{ $i <= $existingReview->rating ? '' : '-o' }} star{{ $i <= $existingReview->rating ? ' filled' : '' }}" data-value="{{ $i }}"></i>
                                        @endfor
                                    </div>
                                    <textarea name="comment" style="width:100%;padding:12px;border:1px solid #ddd;border-radius:8px;font-size:0.9rem;resize:vertical;min-height:80px;margin-top:10px;font-family:inherit;">{{ $existingReview->comment }}</textarea>
                                    <div class="review-photos-input" style="margin-top:10px;">
                                        <label style="font-size:0.82rem;font-weight:600;color:var(--dark);">Add More Photos</label>
                                        <input type="file" name="photos[]" multiple accept="image/*">
                                    </div>
                                    <div class="review-form-actions" style="margin-top:10px;">
                                        <button type="submit" class="add-to-cart-btn" style="border:none;cursor:pointer;font-size:0.85rem;">Save Changes</button>
                                        <button type="button" class="buy-now-btn" style="border:none;cursor:pointer;font-size:0.85rem;" onclick="toggleEditReview()">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                @endauth

                @if ($reviews->isEmpty())
                    <p style="text-align:center;color:#aaa;padding:30px 0;font-size:0.9rem;">No reviews yet. Be the first to review this product!</p>
                @else
                    @foreach ($reviews as $review)
                        <div class="review-card">
                            <div class="review-header">
                                <div class="review-avatar">{{ strtoupper(substr($review->customer->full_name ?? 'A', 0, 1)) }}</div>
                                <div class="review-meta">
                                    <strong>{{ $review->customer->full_name ?? 'Anonymous' }}</strong>
                                    <span class="review-date">{{ $review->created_at->format('M j, Y') }}</span>
                                </div>
                            </div>
                            <div class="review-stars-static">
                                @for ($i = 1; $i <= 5; $i)<i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>@endfor
                            </div>
                            @if ($review->comment)
                                <p class="review-comment">{{ $review->comment }}</p>
                            @endif
                            @if ($review->photos->isNotEmpty())
                                <div class="review-photos">
                                    @foreach ($review->photos as $photo)
                                        <img src="{{ asset('images/'.$photo->image_url) }}" alt="Review photo" class="review-thumb" onclick="openReviewLightbox('{{ asset('images/'.$photo->image_url) }}')">
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>

            @if ($related->isNotEmpty())
                <h3 class="section-title" style="margin-top:60px;">You May Also Like</h3>
                <div class="catalogue">
                    @foreach ($related as $item)
                        <div class="product-card {{ $item->is_available ? '' : 'is-unavailable' }}" data-href="{{ route('products.show', $item->id) }}">
                            <a href="{{ route('products.show', $item->id) }}" style="text-decoration:none;color:inherit;">
                                <div class="product-img">
                                    <img src="{{ asset('images/'.$item->image_url) }}" alt="{{ $item->name }}" loading="lazy">
                                    @if (! $item->is_available)
                                        <div class="stock-overlay">Not available at the moment</div>
                                    @endif
                                </div>
                            </a>
                            <div class="product-info">
                                <h3>{{ $item->name }}</h3>
                                <div class="product-price">₱{{ number_format($item->price, 2) }}</div>
                                @if ($item->review_count > 0)
                                    <div style="color:#f5a623;font-size:0.8rem;">@for ($i = 1; $i <= 5; $i)<i class="fas fa-star{{ $i <= round($item->average_rating) ? '' : '-o' }}"></i>@endfor <span style="color:#aaa;">({{ $item->review_count }})</span></div>
                                @endif
                                <div class="product-actions">
                                    @if ($item->is_available)
                                        @auth('web')
                                            <button class="add-to-cart-btn" data-id="{{ $item->id }}" data-name="{{ $item->name }}">
                                                <i class="fas fa-cart-plus"></i> Add
                                            </button>
                                            <button class="buy-now-btn" data-id="{{ $item->id }}">
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
                                        <span class="unavailable-btn"><i class="fas fa-exclamation-triangle"></i></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <div class="review-lightbox" id="reviewLightbox" onclick="closeReviewLightbox()">
        <button class="review-lightbox-close">&times;</button>
        <img id="reviewLightboxImg" src="" alt="Review photo enlarged">
    </div>

    @push('scripts')
    <script>
        function openReviewLightbox(src) {
            document.getElementById('reviewLightboxImg').src = src;
            document.getElementById('reviewLightbox').classList.add('show');
        }
        function closeReviewLightbox() {
            document.getElementById('reviewLightbox').classList.remove('show');
        }

        function toggleEditReview() {
            const f = document.getElementById('editReviewForm');
            f.style.display = f.style.display === 'none' ? 'block' : 'none';
        }

        function initStars(container, inputId) {
            if (!container) return;
            container.querySelectorAll('.star').forEach(star => {
                star.addEventListener('click', function() {
                    const val = parseInt(this.dataset.value);
                    document.getElementById(inputId).value = val;
                    container.querySelectorAll('.star').forEach((s, idx) => {
                        s.classList.toggle('filled', idx < val);
                        s.classList.toggle('fas', idx < val);
                        s.classList.toggle('far', idx >= val);
                        s.classList.toggle('fa-star', true);
                        s.classList.toggle('fa-star-o', false);
                    });
                });
                star.addEventListener('mouseenter', function() {
                    const val = parseInt(this.dataset.value);
                    container.querySelectorAll('.star').forEach((s, idx) => {
                        if (idx < val) s.style.color = '#f5a623';
                    });
                });
                star.addEventListener('mouseleave', function() {
                    container.querySelectorAll('.star').forEach((s, idx) => {
                        s.style.color = '';
                    });
                });
            });
        }

        initStars(document.getElementById('formStars'), 'ratingInput');
        initStars(document.getElementById('editStars'), 'editRatingInput');

        const photoInput = document.getElementById('reviewPhotos');
        const photoPreview = document.getElementById('photoPreview');
        if (photoInput) {
            photoInput.addEventListener('change', function() {
                photoPreview.innerHTML = '';
                Array.from(this.files).slice(0, 5).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        photoPreview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });
            });
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeReviewLightbox();
        });
    </script>
    @endpush
@endsection
