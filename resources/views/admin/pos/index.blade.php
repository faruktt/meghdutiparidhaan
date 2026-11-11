@extends('layouts.admin')

@section('title', 'POS / New Order')
@section('breadcrumb', 'POS / New Order')

@push('styles')
<style>
    .pos-page-wrapper {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    /* Top Active Branch Card */
    .pos-branch-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        padding: 12px 18px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }
    .pos-branch-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: #FFEDD5;
        color: #EA580C;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }
    .pos-branch-info {
        display: flex;
        flex-direction: column;
        line-height: 1.25;
    }
    .pos-branch-sub {
        font-size: 11px;
        font-weight: 700;
        color: #EA580C;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .pos-branch-title {
        font-size: 15px;
        font-weight: 800;
        color: #1E293B;
        margin: 0;
    }

    /* Filter & Search Bar */
    .pos-filter-bar {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        padding: 8px 14px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }
    .pos-search-box {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 10px;
        position: relative;
    }
    .pos-search-box i {
        color: #94A3B8;
        font-size: 15px;
    }
    .pos-search-input {
        width: 100%;
        border: none;
        outline: none;
        font-size: 14px;
        color: #1E293B;
        background: transparent;
        font-family: inherit;
    }
    .pos-search-input::placeholder {
        color: #94A3B8;
        font-size: 13.5px;
    }
    .pos-category-wrapper {
        border-left: 1px solid #E2E8F0;
        padding-left: 14px;
    }
    .pos-category-select {
        border: 1px solid transparent;
        background: transparent;
        font-size: 13.5px;
        font-weight: 600;
        color: #334155;
        padding: 6px 10px;
        border-radius: 8px;
        outline: none;
        cursor: pointer;
        transition: all 0.2s;
    }
    .pos-category-select:focus,
    .pos-category-select:hover {
        background: #F8FAFC;
        border-color: #CBD5E1;
    }

    /* Main Grid & Cart Layout */
    .pos-main-container {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 380px;
        gap: 18px;
        align-items: start;
    }
    @media (max-width: 1200px) {
        .pos-main-container {
            grid-template-columns: 1fr;
        }
    }

    /* Product Grid */
    .pos-products-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 14px;
    }
    @media (max-width: 1480px) {
        .pos-products-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }
    }
    @media (max-width: 1100px) {
        .pos-products-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }
    @media (max-width: 640px) {
        .pos-products-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    /* Product Card */
    .pos-item-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 14px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
    }
    .pos-item-card:hover {
        border-color: #EA580C;
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(234, 88, 12, 0.12);
    }
    .pos-item-thumb {
        height: 115px;
        background: #F8FAFC;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border-bottom: 1px solid #F1F5F9;
    }
    .pos-item-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    .pos-item-card:hover .pos-item-thumb img {
        transform: scale(1.05);
    }
    .pos-item-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
    }
    .pos-item-placeholder i {
        font-size: 34px;
        color: #CBD5E1;
    }
    .pos-item-body {
        padding: 12px 14px 14px 14px;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .pos-item-name {
        font-size: 13.5px;
        font-weight: 700;
        color: #1E293B;
        line-height: 1.35;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .pos-item-price {
        font-size: 14px;
        font-weight: 800;
        color: #EA580C;
    }

    /* Cart Panel */
    .pos-cart-panel {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 14px;
        display: flex;
        flex-direction: column;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        position: sticky;
        top: 80px;
    }
    .pos-cart-header {
        padding: 14px 18px;
        border-bottom: 1px solid #E2E8F0;
        background: #F8FAFC;
        border-top-left-radius: 14px;
        border-top-right-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .pos-cart-header h3 {
        margin: 0;
        font-size: 15px;
        font-weight: 800;
        color: #1E293B;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .pos-cart-customer {
        padding: 14px 18px;
        border-bottom: 1px solid #E2E8F0;
        background: #FFFFFF;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }
    .pos-cart-customer label {
        font-size: 10.5px;
        font-weight: 700;
        color: #64748B;
        text-transform: uppercase;
        margin-bottom: 4px;
        display: block;
    }
    .pos-cart-customer input {
        width: 100%;
        padding: 6px 10px;
        font-size: 12.5px;
        border: 1px solid #E2E8F0;
        border-radius: 6px;
        outline: none;
    }
    .pos-cart-customer input:focus {
        border-color: #EA580C;
    }
    .pos-cart-items-wrap {
        max-height: 280px;
        overflow-y: auto;
        padding: 14px 18px;
    }
    .pos-cart-item-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px dashed #E2E8F0;
        font-size: 13px;
    }
    .pos-cart-item-row:last-child {
        border-bottom: none;
    }
    .pos-qty-btn {
        width: 24px;
        height: 24px;
        border: 1px solid #CBD5E1;
        background: #F8FAFC;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #334155;
    }
    .pos-qty-btn:hover {
        background: #EA580C;
        border-color: #EA580C;
        color: #FFFFFF;
    }
    .pos-cart-footer {
        padding: 16px 18px;
        border-top: 1px solid #E2E8F0;
        background: #F8FAFC;
        border-bottom-left-radius: 14px;
        border-bottom-right-radius: 14px;
    }
    .pos-btn-submit {
        width: 100%;
        background: #EA580C;
        color: #FFFFFF;
        border: none;
        padding: 12px;
        border-radius: 8px;
        font-weight: 800;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: background 0.2s;
        margin-top: 12px;
    }
    .pos-btn-submit:hover {
        background: #C2410C;
    }
</style>
@endpush

@section('content')
<div class="pos-page-wrapper">

    <!-- Top POS Terminal Header Card -->
    <div class="pos-branch-card" style="display: flex; justify-content: space-between; align-items: center;">
        <div style="display: flex; align-items: center; gap: 14px;">
            <div class="pos-branch-icon" style="background: #FFF7ED; color: var(--admin-primary);">
                <i class="fa-solid fa-cash-register"></i>
            </div>
            <div class="pos-branch-info">
                <span class="pos-branch-sub">Direct Counter Billing</span>
                <h4 class="pos-branch-title">Meghduti Paridhaan - Main POS Counter</h4>
            </div>
        </div>
        <span class="badge" style="background: #F1F5F9; color: #475569; font-weight: 700; padding: 6px 12px; font-size: 11px;">
            <i class="fa-regular fa-clock"></i> {{ date('h:i A') }}
        </span>
    </div>

    <!-- Filter & Search Bar -->
    <div class="pos-filter-bar">
        <div class="pos-search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="posSearch" class="pos-search-input" placeholder="Search item by name or SKU..." oninput="filterPosProducts()">
        </div>
        <div class="pos-category-wrapper">
            <select id="posCategoryFilter" class="pos-category-select" onchange="filterPosProducts()">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Main Container: Products Grid on Left, Cart on Right -->
    <div class="pos-main-container">
        
        <!-- Products Grid -->
        <div>
            <div class="pos-products-grid" id="posProductsList">
                @foreach($products as $product)
                    <div class="pos-item-card" 
                         onclick="openVariantModal({{ $product->id }})" 
                         data-name="{{ strtolower($product->name) }}" 
                         data-sku="{{ strtolower($product->sku ?? '') }}"
                         data-category="{{ $product->category_id }}">
                        
                        <div class="pos-item-thumb">
                            @if($product->thumbnail)
                                <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}">
                            @else
                                <div class="pos-item-placeholder">
                                    <i class="fa-solid fa-utensils"></i>
                                </div>
                            @endif
                        </div>

                        <div class="pos-item-body">
                            <div class="pos-item-name" title="{{ $product->name }}">
                                {{ $loop->iteration }}. {{ $product->name }}
                            </div>
                            <div class="pos-item-price">
                                ৳{{ number_format($product->current_price, 2) }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div id="posNoProductsNotice" style="display: none; text-align: center; padding: 60px 20px; background: white; border-radius: 12px; border: 1px dashed #CBD5E1; margin-top: 10px;">
                <i class="fa-solid fa-magnifying-glass" style="font-size: 32px; color: #94A3B8; margin-bottom: 10px;"></i>
                <div style="font-size: 15px; font-weight: 700; color: #475569;">No items found</div>
                <div style="font-size: 13px; color: #94A3B8;">Please adjust your search or category filter.</div>
            </div>
        </div>

        <!-- Right Side: Order Cart Panel -->
        <div class="pos-cart-panel">
            <div class="pos-cart-header">
                <h3>
                    <i class="fa-solid fa-receipt" style="color: #EA580C;"></i> Current Order
                </h3>
                <button type="button" onclick="clearPosCart()" style="background: none; border: none; font-size: 12px; color: #EF4444; cursor: pointer; font-weight: 600;">
                    <i class="fa-solid fa-rotate-left"></i> Clear
                </button>
            </div>

            <!-- Customer inputs -->
            <div class="pos-cart-customer">
                <div>
                    <label>Customer Name</label>
                    <input type="text" id="posCustomerName" value="Walk-in Customer" placeholder="Customer Name">
                </div>
                <div>
                    <label>Phone Number</label>
                    <input type="text" id="posCustomerPhone" value="01700000000" placeholder="Phone Number">
                </div>
            </div>

            <!-- Cart Items List -->
            <div class="pos-cart-items-wrap" id="posCartItems">
                <div style="text-align: center; color: #94A3B8; padding: 40px 10px;" id="emptyCartNotice">
                    <i class="fa-solid fa-bag-shopping" style="font-size: 32px; opacity: 0.35; margin-bottom: 8px;"></i>
                    <p style="font-size: 13px; margin: 0;">No items added yet.<br>Click an item from the list to add to cart.</p>
                </div>
            </div>

            <!-- Total Calculation & Complete Button -->
            <div class="pos-cart-footer">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 13px;">
                    <span style="color: #64748B;">Subtotal:</span>
                    <span style="font-weight: 700; color: #1E293B;">৳<span id="posSubtotal">0.00</span></span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; font-size: 13px;">
                    <span style="color: #64748B;">Payment Method:</span>
                    <select id="posPaymentMethod" style="border: 1px solid #CBD5E1; border-radius: 6px; padding: 4px 8px; font-size: 12px; outline: none; background: #FFFFFF; font-weight: 600;">
                        <option value="cash">Cash</option>
                        <option value="bkash">bKash</option>
                        <option value="nagad">Nagad</option>
                        <option value="card">POS Card</option>
                    </select>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 16px; font-weight: 800; color: #EA580C; border-top: 1px dashed #CBD5E1; padding-top: 10px;">
                    <span>Total Payable:</span>
                    <span>৳<span id="posGrandTotal">0.00</span></span>
                </div>

                <button type="button" onclick="submitPosOrder()" class="pos-btn-submit">
                    <i class="fa-solid fa-circle-check"></i> Complete Order
                </button>
            </div>
        </div>

    </div>
</div>

<!-- Variant Selection Modal -->
<div id="variantModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.55); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(2px);">
    <div style="background: white; border-radius: 14px; width: 440px; max-width: 90%; padding: 22px; box-shadow: 0 20px 40px rgba(0,0,0,0.25);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
            <h3 id="modalProductName" style="font-size: 15px; font-weight: 800; color: #1E293B; margin: 0;">Select Variant</h3>
            <button type="button" onclick="closeVariantModal()" style="background: none; border: none; font-size: 18px; color: #94A3B8; cursor: pointer;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div id="modalVariantList" style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 18px; max-height: 280px; overflow-y: auto;">
        </div>
        <div style="text-align: right;">
            <button type="button" class="btn-admin-secondary" onclick="closeVariantModal()" style="padding: 6px 14px; border-radius: 6px; font-size: 12px; cursor: pointer; border: 1px solid #CBD5E1; background: #F8FAFC;">
                Cancel
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let posCart = [];

    function filterPosProducts() {
        const q = (document.getElementById('posSearch').value || '').trim().toLowerCase();
        const cat = document.getElementById('posCategoryFilter').value;
        const cards = document.querySelectorAll('.pos-item-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.getAttribute('data-name') || '';
            const sku = card.getAttribute('data-sku') || '';
            const cardCat = card.getAttribute('data-category') || '';

            const matchesQuery = !q || name.includes(q) || sku.includes(q);
            const matchesCategory = !cat || cardCat === cat;

            if (matchesQuery && matchesCategory) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        const noResult = document.getElementById('posNoProductsNotice');
        if (noResult) {
            noResult.style.display = visibleCount === 0 ? 'block' : 'none';
        }
    }

    function openVariantModal(productId) {
        fetch(`/admin/pos/product-variants?product_id=${productId}`)
            .then(res => res.json())
            .then(data => {
                const variants = data.variants;
                if (!variants || variants.length === 0) {
                    addToPosCart(data.product.id, null, data.product.name, data.price);
                    return;
                }
                document.getElementById('modalProductName').innerText = data.product.name;
                const list = document.getElementById('modalVariantList');
                list.innerHTML = '';
                variants.forEach(v => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.style.textAlign = 'left';
                    btn.style.display = 'flex';
                    btn.style.justifyContent = 'space-between';
                    btn.style.alignItems = 'center';
                    btn.style.padding = '10px 14px';
                    btn.style.border = '1px solid #E2E8F0';
                    btn.style.borderRadius = '8px';
                    btn.style.background = '#F8FAFC';
                    btn.style.cursor = 'pointer';
                    btn.style.transition = 'all 0.2s';

                    btn.onmouseover = () => {
                        btn.style.borderColor = '#EA580C';
                        btn.style.background = '#FFF7ED';
                    };
                    btn.onmouseout = () => {
                        btn.style.borderColor = '#E2E8F0';
                        btn.style.background = '#F8FAFC';
                    };

                    const variantTitle = `${v.size ? `Size: ${v.size}` : ''} ${v.color ? `Color: ${v.color}` : ''}`.trim() || 'Default';
                    btn.innerHTML = `
                        <div>
                            <div style="font-weight: 700; font-size: 13px; color: #1E293B;">${variantTitle}</div>
                            <div style="font-size: 11px; color: #64748B;">Stock: ${v.stock}</div>
                        </div>
                        <div style="font-weight: 800; color: #EA580C; font-size: 13.5px;">৳${parseFloat(v.price).toFixed(2)}</div>
                    `;

                    btn.onclick = () => {
                        addToPosCart(data.product.id, v.id, `${data.product.name} (${variantTitle})`, v.price);
                        closeVariantModal();
                    };
                    list.appendChild(btn);
                });
                document.getElementById('variantModal').style.display = 'flex';
            })
            .catch(err => {
                console.error(err);
            });
    }

    function closeVariantModal() {
        document.getElementById('variantModal').style.display = 'none';
    }

    function addToPosCart(productId, variantId, title, price) {
        const key = `${productId}_${variantId || 0}`;
        const existing = posCart.find(i => i.key === key);
        if (existing) {
            existing.quantity++;
        } else {
            posCart.push({
                key: key,
                product_id: productId,
                product_variant_id: variantId,
                title: title,
                price: parseFloat(price),
                quantity: 1
            });
        }
        renderPosCart();
    }

    function renderPosCart() {
        const container = document.getElementById('posCartItems');
        if (posCart.length === 0) {
            container.innerHTML = `
                <div style="text-align: center; color: #94A3B8; padding: 40px 10px;" id="emptyCartNotice">
                    <i class="fa-solid fa-bag-shopping" style="font-size: 32px; opacity: 0.35; margin-bottom: 8px;"></i>
                    <p style="font-size: 13px; margin: 0;">No items added yet.<br>Click an item from the list to add to cart.</p>
                </div>`;
            document.getElementById('posSubtotal').innerText = '0.00';
            document.getElementById('posGrandTotal').innerText = '0.00';
            return;
        }

        let html = '';
        let total = 0;
        posCart.forEach((item, index) => {
            const lineTotal = item.price * item.quantity;
            total += lineTotal;
            html += `
                <div class="pos-cart-item-row">
                    <div style="flex: 1; padding-right: 10px;">
                        <div style="font-weight: 700; color: #1E293B; line-height: 1.3;">${item.title}</div>
                        <div style="color: #EA580C; font-weight: 700; font-size: 12px; margin-top: 2px;">
                            ৳${item.price.toFixed(2)} × ${item.quantity} = ৳${lineTotal.toFixed(2)}
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <button type="button" onclick="changePosQty(${index}, -1)" class="pos-qty-btn">-</button>
                        <span style="font-weight: 700; width: 20px; text-align: center; font-size: 13px;">${item.quantity}</span>
                        <button type="button" onclick="changePosQty(${index}, 1)" class="pos-qty-btn">+</button>
                        <button type="button" onclick="removePosItem(${index})" style="background: none; border: none; color: #EF4444; margin-left: 6px; cursor: pointer; font-size: 13px;">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>`;
        });
        container.innerHTML = html;
        document.getElementById('posSubtotal').innerText = total.toFixed(2);
        document.getElementById('posGrandTotal').innerText = total.toFixed(2);
    }

    function changePosQty(index, change) {
        posCart[index].quantity += change;
        if (posCart[index].quantity <= 0) {
            posCart.splice(index, 1);
        }
        renderPosCart();
    }

    function removePosItem(index) {
        posCart.splice(index, 1);
        renderPosCart();
    }

    function clearPosCart() {
        if (posCart.length > 0 && confirm('Are you sure you want to clear the cart?')) {
            posCart = [];
            renderPosCart();
        }
    }

    function submitPosOrder() {
        if (posCart.length === 0) {
            alert('Please add at least one item to the cart.');
            return;
        }

        const data = {
            customer_name: document.getElementById('posCustomerName').value,
            customer_phone: document.getElementById('posCustomerPhone').value,
            payment_method: document.getElementById('posPaymentMethod').value,
            items: posCart.map(i => ({
                product_id: i.product_id,
                product_variant_id: i.product_variant_id,
                quantity: i.quantity
            }))
        };

        const submitBtn = document.querySelector('.pos-btn-submit');
        const origText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Processing...';

        fetch("{{ route('admin.pos.order') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                "Accept": "application/json"
            },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(res => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = origText;
            if (res.success) {
                alert(`Order placed successfully! Order #${res.order_number}`);
                posCart = [];
                renderPosCart();
                window.open(`/admin/orders/${res.order_id}/invoice`, '_blank');
            } else {
                alert('Error creating order: ' + (res.message || 'Unknown error'));
            }
        })
        .catch(err => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = origText;
            console.error(err);
            alert('Failed to process order.');
        });
    }
</script>
@endpush
