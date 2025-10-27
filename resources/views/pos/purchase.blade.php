@extends('layouts.app')
@section('title', 'POS - Purchase')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Create Purchase</h5>
    <a href="{{ route('pos.sell') }}" class="btn btn-outline-secondary btn-sm">Sell</a>
  </div>

  <div class="card-body">
    <form method="POST" action="{{ route('pos.purchase.store') }}" id="purchase-form">
      @csrf

      {{-- Top section --}}
      <div class="row g-3">
        <div class="col-md-3">
          <label class="form-label">Supplier</label>
          <select name="party_id" class="form-select">
            <option value="">None</option>
            @foreach($suppliers as $s)
              <option value="{{ $s->id }}">{{ $s->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-md-3">
          <label class="form-label">Warehouse</label>
          <select name="warehouse_id" class="form-select" required>
            @foreach($warehouses as $w)
              <option value="{{ $w->id }}">{{ $w->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-md-3">
          <label class="form-label">Date</label>
          <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
        </div>

        <div class="col-md-3">
          <label class="form-label">Scan Barcode / SKU</label>
          <input type="text" id="scan" class="form-control" placeholder="Scan here">
        </div>
      </div>

      {{-- Items Table --}}
      <div class="table-responsive mt-3">
        <table class="table" id="items-table">
          <thead>
            <tr>
              <th style="width:35%">Product</th>
              <th style="width:15%">Cost</th>
              <th style="width:15%">Qty</th>
              <th style="width:15%">Total</th>
              <th style="width:10%"></th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <button type="button" class="btn btn-sm btn-outline-primary" id="btn-add">Add Item</button>
      </div>

      {{-- Totals --}}
      <div class="row mt-3">
        <div class="col-md-3">
          <label class="form-label">Discount</label>
          <input type="number" step="0.01" name="discount" class="form-control" value="0">
        </div>
        <div class="col-md-3">
          <label class="form-label">Tax</label>
          <input type="number" step="0.01" name="tax" class="form-control" value="0">
        </div>
        <div class="col-md-3">
          <label class="form-label">Shipping</label>
          <input type="number" step="0.01" name="shipping" class="form-control" value="0">
        </div>
        <div class="col-md-3">
          <label class="form-label">Grand Total</label>
          <input type="text" id="grand-total" class="form-control" value="0" readonly>
        </div>
      </div>

      {{-- Payment --}}
      <div class="row mt-3 g-3">
        <div class="col-md-3">
          <label class="form-label">Payment Account</label>
          <select name="payment[account_id]" class="form-select">
            <option value="">No Immediate Payment</option>
            @foreach($accounts as $a)
              <option value="{{ $a->id }}">{{ $a->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Payment Amount</label>
          <input type="number" step="0.01" name="payment[amount]" class="form-control" value="0">
        </div>
        <div class="col-md-3">
          <label class="form-label">Method</label>
          <select name="payment[method]" class="form-select">
            <option value="cash">Cash</option>
            <option value="bank_transfer">Bank Transfer</option>
            <option value="cheque">Cheque</option>
            <option value="card">Card</option>
            <option value="mobile_wallet">Mobile Wallet</option>
          </select>
        </div>
      </div>

      <div class="mt-4 d-flex justify-content-end gap-2">
        <button type="submit" class="btn btn-primary">Save Purchase</button>
      </div>
    </form>
  </div>
</div>

{{-- =============== JAVASCRIPT SECTION =============== --}}
<script>
const products = @json($products);

function addRow(prod = null) {
  const tbody = document.querySelector('#items-table tbody');
  const rowIndex = tbody.querySelectorAll('tr').length;
  const tr = document.createElement('tr');

  tr.innerHTML = `
    <td>
      <select class="form-select prod-select" name="items[${rowIndex}][product_id]" required>
        <option value="">Select</option>
        ${products.map(p =>
          `<option value="${p.id}" data-cost="${p.cost_price}">${p.name} (${p.sku})</option>`
        ).join('')}
      </select>
    </td>
    <td><input type="number" step="0.01" class="form-control cost" name="items[${rowIndex}][cost]" value="0" required min="0"></td>
    <td><input type="number" step="0.001" class="form-control qty" name="items[${rowIndex}][quantity]" value="1" required min="0.001"></td>
    <td><input type="text" class="form-control line-total" readonly value="0"></td>
    <td><button type="button" class="btn btn-sm btn-danger btn-del">X</button></td>
  `;

  tbody.appendChild(tr);

  const select = tr.querySelector('.prod-select');
  const cost = tr.querySelector('.cost');
  const qty = tr.querySelector('.qty');
  const total = tr.querySelector('.line-total');

  select.addEventListener('change', () => {
    const opt = select.options[select.selectedIndex];
    cost.value = opt.getAttribute('data-cost') || 0;
    recalc();
  });

  [cost, qty].forEach(el => el.addEventListener('input', recalc));
  tr.querySelector('.btn-del').addEventListener('click', () => { tr.remove(); recalc(); });

  if (prod) {
    select.value = String(prod.id);
    cost.value = prod.cost_price;
  }

  recalc();
}

function recalc() {
  let subtotal = 0;
  document.querySelectorAll('#items-table tbody tr').forEach(tr => {
    const cost = parseFloat(tr.querySelector('.cost').value || 0);
    const qty = parseFloat(tr.querySelector('.qty').value || 0);
    const lt = cost * qty;
    tr.querySelector('.line-total').value = lt.toFixed(2);
    subtotal += lt;
  });
  const discount = parseFloat(document.querySelector('input[name="discount"]').value || 0);
  const tax = parseFloat(document.querySelector('input[name="tax"]').value || 0);
  const shipping = parseFloat(document.querySelector('input[name="shipping"]').value || 0);
  document.querySelector('#grand-total').value = Math.max(0, subtotal - discount + tax + shipping).toFixed(2);
}

// Add item manually
document.getElementById('btn-add').addEventListener('click', () => addRow());
addRow(); // start with one empty row

// Scan barcode or SKU
document.getElementById('scan').addEventListener('keypress', async (e) => {
  if (e.key === 'Enter') {
    e.preventDefault();
    const q = e.target.value.trim();
    if (!q) return;
    try {
      const res = await fetch(`{{ route('pos.lookup.product') }}?q=${encodeURIComponent(q)}`);
      if (!res.ok) throw new Error('not found');
      const p = await res.json();
      addRow(p);
      e.target.value = '';
    } catch (err) {
      alert('Product not found');
    }
  }
});

// Update totals live
['discount', 'tax', 'shipping'].forEach(name => {
  document.querySelector(`input[name="${name}"]`).addEventListener('input', recalc);
});

// Clean up invalid rows & reindex before submitting
document.getElementById('purchase-form').addEventListener('submit', (e) => {
  const tbody = document.querySelector('#items-table tbody');
  const rows = Array.from(tbody.querySelectorAll('tr'));
  let validRows = [];

  rows.forEach(tr => {
    const prodId = tr.querySelector('.prod-select')?.value;
    const qty = parseFloat(tr.querySelector('.qty')?.value || 0);
    const cost = parseFloat(tr.querySelector('.cost')?.value || 0);
    if (prodId && qty > 0 && cost >= 0) validRows.push(tr);
    else tr.remove();
  });

  if (validRows.length === 0) {
    e.preventDefault();
    alert('Please add at least one valid item (product, quantity, cost).');
    return;
  }

  // reindex input names
  validRows.forEach((tr, i) => {
    tr.querySelectorAll('input, select').forEach(inp => {
      inp.name = inp.name.replace(/items\[\d+\]/, `items[${i}]`);
    });
  });
});
</script>
@endsection
