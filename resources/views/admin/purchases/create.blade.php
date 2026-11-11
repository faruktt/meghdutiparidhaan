@extends('layouts.admin')

@section('title', 'Record Inventory Purchase')

@section('content')
<div class="card-header-flex">
    <div>
        <a href="{{ route('admin.purchases.index') }}" style="font-size: 13px; color: var(--text-muted); text-decoration: none;">
            <i class="fa-solid fa-arrow-left"></i> Back to Purchases
        </a>
        <h1 style="font-size: 22px; font-weight: 800; color: var(--text-dark); margin-top: 4px;">Record Inventory Purchase</h1>
    </div>
</div>

<form action="{{ route('admin.purchases.store') }}" method="POST">
    @csrf
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; @media(max-width: 900px){grid-template-columns: 1fr;}">
        <div>
            <!-- Purchase Items -->
            <div class="card">
                <div class="card-header-flex">
                    <h3 class="card-title">Purchased Items & Restock</h3>
                    <button type="button" class="btn-admin-secondary" onclick="addPurchaseRow()" style="font-size: 12px;">
                        <i class="fa-solid fa-plus"></i> Add Item Row
                    </button>
                </div>
                <p style="font-size: 12.5px; color: var(--text-muted); margin-bottom: 16px;">
                    Adding items here will immediately increment the warehouse stock of the selected variants.
                </p>

                <div id="purchaseItemsList" style="display: flex; flex-direction: column; gap: 12px;">
                    <div class="purchase-row" style="display: grid; grid-template-columns: 2fr 1fr 1fr 30px; gap: 10px; align-items: center;">
                        <select name="items[0][product_id]" class="form-control" required>
                            <option value="">Select Product</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                        <input type="number" name="items[0][cost_price]" class="form-control" placeholder="Cost Price (৳)" required>
                        <input type="number" name="items[0][quantity]" class="form-control" placeholder="Qty" value="10" min="1" required>
                        <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; color: #EF4444; cursor: pointer;"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <!-- Supplier & Payment Details -->
            <div class="card">
                <h3 class="card-title" style="margin-bottom: 16px;">Purchase Invoice Info</h3>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 6px;">Supplier</label>
                    <select name="supplier_id" class="form-control">
                        <option value="">Direct / Cash Purchase</option>
                        @foreach($suppliers as $sup)
                            <option value="{{ $sup->id }}">{{ $sup->name }} @if($sup->company_name) ({{ $sup->company_name }}) @endif</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 6px;">Purchase Date <span style="color: red;">*</span></label>
                    <input type="date" name="purchase_date" value="{{ date('Y-m-d') }}" class="form-control" required>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 6px;">Paid Amount (৳)</label>
                    <input type="number" step="0.01" name="paid_amount" class="form-control" placeholder="0">
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 6px;">Payment Status</label>
                    <select name="payment_status" class="form-control">
                        <option value="paid">Paid Full</option>
                        <option value="partial">Partial</option>
                        <option value="unpaid">Unpaid / Due</option>
                    </select>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 6px;">Note / Memo</label>
                    <textarea name="note" rows="2" class="form-control" placeholder="Invoice reference, transport note..."></textarea>
                </div>

                <button type="submit" class="btn-admin-primary" style="width: 100%; justify-content: center; padding: 14px; font-size: 15px;">
                    <i class="fa-solid fa-check"></i> Record & Increase Stock
                </button>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    let pRowIndex = 1;
    function addPurchaseRow() {
        const container = document.getElementById('purchaseItemsList');
        const row = document.createElement('div');
        row.className = 'purchase-row';
        row.style.cssText = 'display: grid; grid-template-columns: 2fr 1fr 1fr 30px; gap: 10px; align-items: center;';
        row.innerHTML = `
            <select name="items[${pRowIndex}][product_id]" class="form-control" required>
                <option value="">Select Product</option>
                @foreach($products as $p)
                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
            </select>
            <input type="number" name="items[${pRowIndex}][cost_price]" class="form-control" placeholder="Cost Price (৳)" required>
            <input type="number" name="items[${pRowIndex}][quantity]" class="form-control" placeholder="Qty" value="10" min="1" required>
            <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; color: #EF4444; cursor: pointer;"><i class="fa-solid fa-xmark"></i></button>
        `;
        container.appendChild(row);
        pRowIndex++;
    }
</script>
@endpush
