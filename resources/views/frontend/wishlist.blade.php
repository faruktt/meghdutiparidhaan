@extends('layouts.app')

@section('title', 'My Wishlist | ' . \App\Models\Setting::get('site_name', 'Meghduti Paridhaan'))

@push('styles')
<style>
    .wishlist-page-container {
        max-width: 1200px;
        margin: 24px auto 60px;
        padding: 0 16px;
    }
    .wishlist-breadcrumb {
        font-size: 13px;
        color: #6B7280;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .wishlist-breadcrumb a {
        color: #374151;
        text-decoration: none;
    }
    .wishlist-breadcrumb a:hover {
        color: var(--brand-green);
    }
    .wishlist-header-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid #E5E7EB;
        flex-wrap: wrap;
        gap: 12px;
    }
    .wishlist-title {
        font-size: 24px;
        font-weight: 800;
        color: #111827;
        font-family: 'Playfair Display', 'Hind Siliguri', serif;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .wishlist-title i {
        color: #EF4444;
    }
    .wishlist-count-badge {
        font-size: 13px;
        font-weight: 700;
        background: #FEE2E2;
        color: #DC2626;
        padding: 2px 10px;
        border-radius: 99px;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .btn-clear-wishlist {
        background: #FFFFFF;
        border: 1px solid #E5E7EB;
        color: #6B7280;
        padding: 7px 16px;
        border-radius: 4px;
        font-size: 12.5px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }
    .btn-clear-wishlist:hover {
        border-color: #EF4444;
        color: #EF4444;
        background: #FEF2F2;
    }

    /* Wishlist Table Card */
    .wishlist-card {
        background: #FFFFFF;
        border-radius: 8px;
        border: 1px solid #E5E7EB;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        overflow: hidden;
    }
    .wishlist-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }
    .wishlist-table th {
        background: #F9FAFB;
        padding: 14px 20px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        color: #4B5563;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #E5E7EB;
    }
    .wishlist-table td {
        padding: 16px 20px;
        border-bottom: 1px solid #F3F4F6;
        vertical-align: middle;
        font-size: 13.5px;
    }
    .wishlist-table tr:last-child td {
        border-bottom: none;
    }
    .wishlist-table tr {
        transition: background-color 0.2s;
    }
    .wishlist-table tr:hover {
        background: #FAFAFA;
    }

    .wishlist-item-meta {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .wishlist-img {
        width: 70px;
        height: 85px;
        object-fit: cover;
        border-radius: 4px;
        background: #F3F4F6;
        border: 1px solid #E5E7EB;
        flex-shrink: 0;
    }
    .wishlist-name {
        font-weight: 700;
        color: #111827;
        text-decoration: none;
        display: block;
        line-height: 1.3;
        margin-bottom: 4px;
    }
    .wishlist-name:hover {
        color: var(--brand-green);
    }
    .wishlist-cat {
        font-size: 11.5px;
        color: #6B7280;
    }

    .wishlist-price-val {
        font-weight: 800;
        color: var(--brand-green);
        font-size: 15px;
    }
    .wishlist-price-regular {
        font-size: 12px;
        color: #9CA3AF;
        text-decoration: line-through;
        margin-left: 4px;
    }

    .badge-in-stock {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 8px;
        border-radius: 4px;
        background: #DCFCE7;
        color: #15803D;
        font-size: 11.5px;
        font-weight: 700;
    }
    .badge-out-stock {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 8px;
        border-radius: 4px;
        background: #FEE2E2;
        color: #DC2626;
        font-size: 11.5px;
        font-weight: 700;
    }

    .wishlist-actions-cell {
        display: flex;
        align-items: center;
        gap: 10px;
        justify-content: flex-end;
    }
    .btn-wishlist-cart {
        background: var(--brand-green);
        color: #FFFFFF;
        border: none;
        padding: 8px 16px;
        border-radius: 4px;
        font-size: 12.5px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: background 0.2s;
        text-decoration: none;
    }
    .btn-wishlist-cart:hover {
        background: var(--brand-green-hover);
        color: #FFFFFF;
    }
    .btn-wishlist-remove {
        background: none;
        border: 1px solid #E5E7EB;
        color: #9CA3AF;
        width: 34px;
        height: 34px;
        border-radius: 4px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .btn-wishlist-remove:hover {
        border-color: #EF4444;
        color: #EF4444;
        background: #FEF2F2;
    }

    /* Empty State */
    .wishlist-empty-box {
        background: #FFFFFF;
        border-radius: 8px;
        border: 1px solid #E5E7EB;
        padding: 60px 20px;
        text-align: center;
    }
    .wishlist-empty-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #FEF2F2;
        color: #EF4444;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        margin-bottom: 20px;
    }
    .wishlist-empty-title {
        font-size: 20px;
        font-weight: 800;
        color: #111827;
        margin-bottom: 8px;
    }
    .wishlist-empty-desc {
        font-size: 13.5px;
        color: #6B7280;
        max-width: 440px;
        margin: 0 auto 24px;
        line-height: 1.5;
    }
    .btn-wishlist-shop {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--brand-green);
        color: #FFFFFF;
        padding: 11px 26px;
        border-radius: 4px;
        font-size: 13.5px;
        font-weight: 700;
        text-decoration: none;
        transition: background 0.2s;
    }
    .btn-wishlist-shop:hover {
        background: var(--brand-green-hover);
        color: #FFFFFF;
    }

    /* Responsive Mobile styles */
    @media (max-width: 768px) {
        .wishlist-table thead {
            display: none;
        }
        .wishlist-table, .wishlist-table tbody, .wishlist-table tr, .wishlist-table td {
            display: block;
            width: 100%;
            box-sizing: border-box;
        }
        .wishlist-table tr {
            padding: 16px;
            border-bottom: 1px solid #E5E7EB;
            position: relative;
        }
        .wishlist-table td {
            padding: 6px 0;
            border: none;
        }
        .wishlist-actions-cell {
            justify-content: space-between;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px dashed #E5E7EB;
        }
        .btn-wishlist-cart {
            flex: 1;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="wishlist-page-container">
    
    <!-- Breadcrumb -->
    <div class="wishlist-breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span><i class="fa-solid fa-angle-right" style="font-size: 10px;"></i></span>
        <span style="color: #111827; font-weight: 600;">My Wishlist</span>
    </div>

    <!-- Header bar -->
    <div class="wishlist-header-bar">
        <div class="wishlist-title">
            <i class="fa-solid fa-heart"></i>
            <span>My Wishlist</span>
            <span class="wishlist-count-badge" id="wishlistPageCount">{{ $products->count() }}</span>
        </div>

        @if($products->count() > 0)
            <button type="button" class="btn-clear-wishlist" onclick="clearWishlist()">
                <i class="fa-regular fa-trash-can"></i> Clear All
            </button>
        @endif
    </div>

    @if($products->isEmpty())
        <!-- Empty State -->
        <div class="wishlist-empty-box" id="wishlistEmptyState">
            <div class="wishlist-empty-icon">
                <i class="fa-regular fa-heart"></i>
            </div>
            <h3 class="wishlist-empty-title">Your Wishlist is Empty</h3>
            <p class="wishlist-empty-desc">
                You haven't added any products to your wishlist yet. Explore our exclusive boutique collections and save your favorite sarees & dresses!
            </p>
            <a href="{{ route('shop.index') }}" class="btn-wishlist-shop">
                <i class="fa-solid fa-bag-shopping"></i> Start Shopping
            </a>
        </div>
    @else
        <!-- Wishlist List -->
        <div class="wishlist-card" id="wishlistCardWrap">
            <table class="wishlist-table">
                <thead>
                    <tr>
                        <th style="width: 45%;">Product</th>
                        <th style="width: 20%;">Price</th>
                        <th style="width: 15%;">Stock Status</th>
                        <th style="width: 20%; text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody id="wishlistTableBody">
                    @foreach($products as $product)
                        <tr id="wishlist-row-{{ $product->id }}">
                            <!-- Product Column -->
                            <td>
                                <div class="wishlist-item-meta">
                                    <a href="{{ route('product.show', $product->slug) }}">
                                        <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}" class="wishlist-img" loading="lazy">
                                    </a>
                                    <div>
                                        <a href="{{ route('product.show', $product->slug) }}" class="wishlist-name">
                                            {{ $product->name }}
                                        </a>
                                        <div class="wishlist-cat">
                                            {{ $product->category?->name ?? 'Boutique' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Price Column -->
                            <td>
                                <div>
                                    <span class="wishlist-price-val">৳{{ number_format($product->current_price) }}</span>
                                    @if($product->sale_price && $product->regular_price > $product->sale_price)
                                        <span class="wishlist-price-regular">৳{{ number_format($product->regular_price) }}</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Stock Status Column -->
                            <td>
                                @if($product->total_stock > 0)
                                    <span class="badge-in-stock">
                                        <i class="fa-solid fa-check"></i> In Stock
                                    </span>
                                @else
                                    <span class="badge-out-stock">
                                        <i class="fa-solid fa-xmark"></i> Out of Stock
                                    </span>
                                @endif
                            </td>

                            <!-- Action Column -->
                            <td>
                                <div class="wishlist-actions-cell">
                                    @if($product->total_stock > 0)
                                        <button type="button" class="btn-wishlist-cart" onclick="addToCart({{ $product->id }}, 1)">
                                            <i class="fa-solid fa-cart-shopping"></i> Add to Cart
                                        </button>
                                    @else
                                        <button type="button" class="btn-wishlist-cart" style="background: #9CA3AF; cursor: not-allowed;" disabled>
                                            Out of Stock
                                        </button>
                                    @endif

                                    <button type="button" class="btn-wishlist-remove" onclick="removeWishlistItem({{ $product->id }})" title="Remove item">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Hidden template for empty state when all rows are deleted via AJAX -->
        <div class="wishlist-empty-box" id="wishlistEmptyState" style="display: none;">
            <div class="wishlist-empty-icon">
                <i class="fa-regular fa-heart"></i>
            </div>
            <h3 class="wishlist-empty-title">Your Wishlist is Empty</h3>
            <p class="wishlist-empty-desc">
                You haven't added any products to your wishlist yet. Explore our exclusive boutique collections and save your favorite sarees & dresses!
            </p>
            <a href="{{ route('shop.index') }}" class="btn-wishlist-shop">
                <i class="fa-solid fa-bag-shopping"></i> Start Shopping
            </a>
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
    function removeWishlistItem(productId) {
        if (!confirm('Remove this product from your wishlist?')) return;

        fetch(`/wishlist/remove/${productId}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                "Accept": "application/json"
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(data.message);

                const row = document.getElementById(`wishlist-row-${productId}`);
                if (row) {
                    row.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    row.style.opacity = '0';
                    row.style.transform = 'translateX(20px)';
                    setTimeout(() => {
                        row.remove();
                        checkEmptyWishlist(data.count);
                    }, 300);
                } else {
                    checkEmptyWishlist(data.count);
                }

                // Update header wishlist count
                const headerCount = document.getElementById('headerWishlistCount');
                if (headerCount) {
                    headerCount.innerText = data.count;
                }

                // Update page count badge
                const pageCount = document.getElementById('wishlistPageCount');
                if (pageCount) {
                    pageCount.innerText = data.count;
                }
            }
        })
        .catch(err => {
            console.error('Error removing from wishlist:', err);
        });
    }

    function clearWishlist() {
        if (!confirm('Are you sure you want to clear your entire wishlist?')) return;

        fetch('/wishlist/clear', {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                "Accept": "application/json"
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(data.message);
                checkEmptyWishlist(0);

                const headerCount = document.getElementById('headerWishlistCount');
                if (headerCount) headerCount.innerText = '0';
            }
        });
    }

    function checkEmptyWishlist(count) {
        const body = document.getElementById('wishlistTableBody');
        const cardWrap = document.getElementById('wishlistCardWrap');
        const emptyState = document.getElementById('wishlistEmptyState');
        const clearBtn = document.querySelector('.btn-clear-wishlist');
        const pageCount = document.getElementById('wishlistPageCount');

        if (pageCount) pageCount.innerText = count;

        if (count === 0 || !body || body.children.length === 0) {
            if (cardWrap) cardWrap.style.display = 'none';
            if (emptyState) emptyState.style.display = 'block';
            if (clearBtn) clearBtn.style.display = 'none';
        }
    }
</script>
@endpush

